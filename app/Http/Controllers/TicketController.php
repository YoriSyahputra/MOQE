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
        $validatedData = $request->validate([
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

        // Handling file uploads
        if ($request->hasFile('evidence_path')) {
            $validatedData['evidence_path'] = $request->file('evidence_path')->store('evidences', 'public');
        }

        if ($request->hasFile('surat_pihak_ketiga_path')) {
            $validatedData['surat_pihak_ketiga_path'] = $request->file('surat_pihak_ketiga_path')->store('surat_pihak_ketiga', 'public');
        }

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
        $validatedData = $request->validate([
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

        // Handling file updates
        if ($request->hasFile('evidence_path')) {
            // Delete old file
            Storage::disk('public')->delete($ticket->evidence_path);
            // Store new file
            $validatedData['evidence_path'] = $request->file('evidence_path')->store('evidences', 'public');
        }

        if ($request->hasFile('surat_pihak_ketiga_path')) {
            // Delete old file
            Storage::disk('public')->delete($ticket->surat_pihak_ketiga_path);
            // Store new file
            $validatedData['surat_pihak_ketiga_path'] = $request->file('surat_pihak_ketiga_path')->store('surat_pihak_ketiga', 'public');
        }

        $ticket->update($validatedData);

        return redirect()->route('ticket.dashboard')->with('success', 'Ticket updated successfully.');
    }

    public function destroy(Ticket $ticket)
    {
        // Delete associated files
        Storage::disk('public')->delete($ticket->evidence_path);
        Storage::disk('public')->delete($ticket->surat_pihak_ketiga_path);

        $ticket->delete();

        return redirect()->route('ticket.dashboard')->with('success', 'Ticket deleted successfully.');
    }

    private function generateTicketId()
    {
        $lastTicket = Ticket::orderBy('id', 'desc')->first();
        
        if ($lastTicket) {
            $lastId = substr($lastTicket->id, 3); // Remove 'INC' prefix
            $nextId = str_pad(intval($lastId) + 1, 8, '0', STR_PAD_LEFT);
        } else {
            $nextId = '00000001';
        }
        
        return 'INC' . $nextId;
    }

    // Additional methods (if needed)
    public function mandal()
    {
        // Implementation for mandal view or action
        return view('ticket.mandal');
    }

    public function reports()
    {
        // Implementation for reports view or action
        return view('ticket.reports');
    }

    public function cancel()
    {
        // Implementation for cancel action
        return view('ticket.cancel');
    }
}