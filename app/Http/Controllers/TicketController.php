<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $tickets = Ticket::latest()->get();
        return view('ticket.dashboard', compact('tickets'));
    }

    public function create()
    {
        return view('ticket.create');
    }
    
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($request->ajax()) {
            return response()->json(['message' => 'Logged out successfully']);
        }

        return redirect('/login');
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateTicketData($request);
        $validatedData['id'] = $this->generateTicketId();

        $ticket = new Ticket($validatedData);
        $ticket->NAMA_LOP = $this->generateNamaLop($ticket);

        $this->handleFileUpload($request, $ticket, 'evidence_path', 'evidences');
        $this->handleFileUpload($request, $ticket, 'surat_pihak_ketiga_path', 'surat_pihak_ketiga');

        $ticket->save();

        return redirect()->route('ticket.dashboard')->with('success', 'Ticket created successfully.');
    }

    public function show(Ticket $ticket)
    {
        return view('ticket.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        return view('ticket.edit', compact('ticket'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validatedData = $this->validateTicketData($request);
        $ticket->fill($validatedData);
        $ticket->NAMA_LOP = $this->generateNamaLop($ticket);

        $this->handleFileUpload($request, $ticket, 'evidence_path', 'evidences');
        $this->handleFileUpload($request, $ticket, 'surat_pihak_ketiga_path', 'surat_pihak_ketiga');

        $ticket->save();

        return redirect()->route('ticket.dashboard')->with('success', 'Ticket updated successfully.');
    }

    public function destroy(Ticket $ticket)
    {
        $this->deleteFile($ticket->evidence_path);
        $this->deleteFile($ticket->surat_pihak_ketiga_path);
        $ticket->delete();

        return redirect()->route('ticket.dashboard')->with('success', 'Ticket deleted successfully.');
    }

    private function validateTicketData(Request $request)
    {
        return $request->validate([
            'sto' => 'required|string|max:255',
            'judul_pengajuan' => 'required|string|max:255',
            'jenis_QE' => 'required|string|in:QE RELOKASI,QE PREVENTIF',
            'detail_QE' => 'required|string|in:KABEL MENJUNTAI,RELOKASI,TIANG MIRING/TIANG ROBOH/TIANG KEROPOS',
            'alamat' => 'required|string',
            'kebutuhan_material' => 'required|string',
            'progress' => 'required|string|in:Need Survey,Need Create BoQ,Done BoQ,Done NDE Pengajuan,Done Input IHLD,SPMK,On progres lapangan,Closed',
            'titik_kordinasi' => 'nullable|string',
            'tingkat_urgensi' => 'required|string|in:Low,Medium,High',
            'pelapor' => 'required|string|max:255',
            'tanggal_pengajuan' => 'required|date|before_or_equal:today',
            'keterangan' => 'nullable|string',
            'evidence_path' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
            'surat_pihak_ketiga_path' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx,xls,xlsx,txt|max:2048',
        ]);
    }

    private function generateTicketId()
    {
        $lastTicket = Ticket::orderBy('id', 'desc')->first();
        $nextId = $lastTicket ? intval(substr($lastTicket->id, 3)) + 1 : 1;
        return 'INC' . str_pad($nextId, 8, '0', STR_PAD_LEFT);
    }

    private function generateNamaLop($ticket)
    {
        return "BDB,{$ticket->sto},{$ticket->jenis_QE},{$ticket->id},{$ticket->judul_pengajuan}";
    }

    private function handleFileUpload(Request $request, Ticket $ticket, string $fieldName, string $directory)
    {
        if ($request->hasFile($fieldName)) {
            $this->deleteFile($ticket->$fieldName);
            
            $file = $request->file($fieldName);
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            
            $file->move(public_path("storage/$directory"), $filename);
            $ticket->$fieldName = "$directory/$filename";
        }
    }

    private function deleteFile(?string $path)
    {
        if ($path && file_exists(public_path("storage/$path"))) {
            unlink(public_path("storage/$path"));
        }
    }

    public function results()
    {
        $tickets = Ticket::all();
        return view('ticket.results', compact('tickets'));
    }

    public function getDetails(Ticket $ticket, Request $request)
    {
        $column = $request->input('column');
        
        $details = '';
        switch ($column) {
            case 'id':
                $details = "Ticket ID: {$ticket->id}<br>Created at: {$ticket->created_at}";
                break;
            case 'sto':
                $details = "STO: {$ticket->sto}<br>Address: {$ticket->alamat}";
                break;
            case 'nama_lop':
                $details = "Nama LOP: {$ticket->NAMA_LOP}<br>Judul Pengajuan: {$ticket->judul_pengajuan}";
                break;
            case 'jenis_qe':
                $details = "Jenis QE: {$ticket->jenis_QE}<br>Detail QE: {$ticket->detail_QE}";
                break;
            case 'tingkat_urgensi':
                $details = "Tingkat Urgensi: {$ticket->tingkat_urgensi}<br>Progress: {$ticket->progress}";
                break;
            case 'pelapor':
                $details = "Pelapor: {$ticket->pelapor}<br>Kebutuhan Material: {$ticket->kebutuhan_material}";
                break;
            case 'tanggal_pengajuan':
                $details = "Tanggal Pengajuan: {$ticket->tanggal_pengajuan}<br>Keterangan: {$ticket->keterangan}";
                break;
            case 'evidence':
                $details = $ticket->evidence_path ? 
                    "Evidence: <a href='" . asset('storage/' . $ticket->evidence_path) . "' target='_blank'>View Evidence</a>" :
                    "No evidence uploaded";
                break;
            case 'surat_pihak_ketiga':
                $details = $ticket->surat_pihak_ketiga_path ? 
                    "Surat Pihak Ketiga: <a href='" . asset('storage/' . $ticket->surat_pihak_ketiga_path) . "' target='_blank'>View Document</a>" :
                    "No document uploaded";
                break;
            default:
                $details = "No additional details available";
        }

        return $details;
    }
}