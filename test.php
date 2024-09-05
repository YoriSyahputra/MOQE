<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    // ... (other methods remain the same)

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

        // Handling file updates and deletions
        if ($request->hasFile('evidence_path')) {
            Storage::disk('public')->delete($ticket->evidence_path);
            $validatedData['evidence_path'] = $request->file('evidence_path')->store('evidences', 'public');
        }

        if ($request->hasFile('surat_pihak_ketiga_path')) {
            Storage::disk('public')->delete($ticket->surat_pihak_ketiga_path);
            $validatedData['surat_pihak_ketiga_path'] = $request->file('surat_pihak_ketiga_path')->store('surat_pihak_ketiga', 'public');
        }

        $ticket->update($validatedData);

        return redirect()->route('ticket.dashboard')->with('success', 'Ticket updated successfully.');
    }

    // ... (other methods remain the same)
}