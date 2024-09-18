@extends('layouts.app')

@section('title', 'MonitoringQE')

@section('content')
<link  rel="stylesheet" href="{{asset ('CSS/button.css')}}"

    <h1>Ticket Details</h1>

    <div class="ticket-details">
        <p><strong>ID:</strong> {{ $ticket->id }}</p>
        <p><strong>Nama LOP:</strong> {{ $ticket->judul_pengajuan }}</p>
        <p><strong>Jenis QE:</strong> {{ $ticket->jenis_QE }}</p>
        <p><strong>Detail QE:</strong> {{ $ticket->detail_QE }}</p>
        <p><strong>STO:</strong> {{ $ticket->sto }}</p>
        <p><strong>Alamat:</strong> {{ $ticket->alamat }}</p>
        <p><strong>Kebutuhan Material:</strong> {{ $ticket->kebutuhan_material }}</p>
        <p><strong>Progress:</strong> {{ $ticket->progress }}</p>
        <p><strong>Titik Koordinat:</strong> {{ $ticket->titik_kordinasi ?? 'N/A' }}</p>
        <p><strong>Tingkat Urgensi:</strong> {{ $ticket->tingkat_urgensi }}</p>
        <p><strong>Pelapor:</strong> {{ $ticket->pelapor }}</p>
        <p><strong>Tanggal Pengajuan:</strong> {{ $ticket->tanggal_pengajuan }}</p>
        <p><strong>Keterangan:</strong> {{ $ticket->keterangan ?? 'N/A' }}</p>
        
        @if($ticket->evidence_path)
            <p><strong>Evidence:</strong></p>
            <div id="evidencePreview"></div>
        @endif

        @if($ticket->surat_pihak_ketiga_path)
            <p><strong>Surat Pihak Ketiga:</strong></p>
            <div id="suratPreview">
                @php
                    $extension = strtolower(pathinfo($ticket->surat_pihak_ketiga_path, PATHINFO_EXTENSION));
                @endphp
                @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                    <img src="{{ asset('storage/' . $ticket->surat_pihak_ketiga_path) }}" alt="Surat Pihak Ketiga" style="max-width:100%; max-height:500px;">
                @elseif($extension === 'pdf')
                    <embed src="{{ asset('storage/' . $ticket->surat_pihak_ketiga_path) }}" type="application/pdf" width="100%" height="600px" />
                @elseif(in_array($extension, ['doc', 'docx']))
                    <iframe src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode(asset('storage/' . $ticket->surat_pihak_ketiga_path)) }}" width="100%" height="600px" frameborder="0"></iframe>
                @else
                    <p>Preview not available. <a href="{{ asset('storage/' . $ticket->surat_pihak_ketiga_path) }}" target="_blank">Download file</a></p>
                @endif
            </div>
        @endif
    </div>

    <div class="actions">
        <a href="{{ route('ticket.edit', $ticket) }}" class="ferari">Edit</a>
        <a href="{{ route('ticket.dashboard') }}" class="toyota">Back to Dashboard</a>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if($ticket->evidence_path)
                displayFile('{{ asset('storage/' . $ticket->evidence_path) }}', 'evidencePreview', '{{ pathinfo($ticket->evidence_path, PATHINFO_EXTENSION) }}');
            @endif
        });

        function displayFile(src, containerId, fileType) {
            const container = document.getElementById(containerId);
            fileType = fileType.toLowerCase();

            if (['jpg', 'jpeg', 'png', 'gif'].includes(fileType)) {
                container.innerHTML = `<img src="${src}" alt="File Preview" style="max-width:100%; max-height:500px;">`;
            } else if (fileType === 'pdf') {
                container.innerHTML = `<embed src="${src}" type="application/pdf" width="100%" height="600px" />`;
            } else {
                container.innerHTML = `<p>Preview not available. <a href="${src}" target="_blank">Download file</a></p>`;
            }
        }
    </script>