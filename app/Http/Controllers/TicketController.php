<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $tickets = Ticket::all();
        return view('ticket.dashboard', compact('tickets'));
    }

    public function create()
    {
        return view('ticket.create');
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateTicketData($request);

        // Handling file uploads
        $validatedData = $this->handleFileUploads($request, $validatedData);

        // Generate a custom ticket ID
        $validatedData['id'] = $this->generateTicketId();

        Ticket::create($validatedData);

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

        // Handling file updates
        $validatedData = $this->handleFileUpdates($request, $ticket, $validatedData);

        $ticket->update($validatedData);

        return redirect()->route('ticket.dashboard')->with('success', 'Ticket updated successfully.');
    }

    public function destroy(Ticket $ticket)
    {
        $this->deleteAssociatedFiles($ticket);
        $ticket->delete();

        return redirect()->route('ticket.dashboard')->with('success', 'Ticket deleted successfully.');
    }

    private function validateTicketData(Request $request)
    {
        return $request->validate([
            'judul_pengajuan' => 'required|string|max:255',
            'jenis_QE' => 'required|string|in:QE RELOKASI,QE PREVENTIF',
            'detail_QE' => 'required|string|in:KABEL MENJUNTAI,RELOKASI,TIANG MIRING/TIANG ROBOH/TIANG KEROPOS',
            'sto' => 'required|string',
            'nomer_ticket_insera' => 'nullable|string',
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

    private function handleFileUploads(Request $request, array $validatedData)
    {
        if ($request->hasFile('evidence_path')) {
            $validatedData['evidence_path'] = $request->file('evidence_path')->store('evidences', 'public');
        }

        if ($request->hasFile('surat_pihak_ketiga_path')) {
            $validatedData['surat_pihak_ketiga_path'] = $request->file('surat_pihak_ketiga_path')->store('surat_pihak_ketiga', 'public');
        }

        return $validatedData;
    }

    private function handleFileUpdates(Request $request, Ticket $ticket, array $validatedData)
    {
        if ($request->hasFile('evidence_path')) {
            $this->deleteFile($ticket->evidence_path);
            $validatedData['evidence_path'] = $request->file('evidence_path')->store('evidences', 'public');
        }

        if ($request->hasFile('surat_pihak_ketiga_path')) {
            $this->deleteFile($ticket->surat_pihak_ketiga_path);
            $validatedData['surat_pihak_ketiga_path'] = $request->file('surat_pihak_ketiga_path')->store('surat_pihak_ketiga', 'public');
        }

        return $validatedData;
    }

    private function deleteAssociatedFiles(Ticket $ticket)
    {
        $this->deleteFile($ticket->evidence_path);
        $this->deleteFile($ticket->surat_pihak_ketiga_path);
    }

    private function deleteFile($filePath)
    {
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
    }

    private function generateTicketId()
    {
        $lastTicket = Ticket::orderBy('id', 'desc')->first();
        
        if ($lastTicket) {
            $lastId = substr($lastTicket->id, 3); // Remove 'INC' prefix
            $nextId = str_pad(intval($lastId) + 4839, 8, '0', STR_PAD_LEFT);
        } else {
            $nextId = '00000001';
        }
        
        return 'INC' . $nextId;
    }

    // Additional methods
    public function mandal()
    {
        return view('ticket.mandal');
    }

    public function reports()
    {
        return view('ticket.reports');
    }

    public function cancel()
    {
        return view('ticket.cancel');
    }
}