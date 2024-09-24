@extends('layouts.app')

@section('title', 'MonitoringQE')

@section('content')
<link rel="stylesheet" href="{{ asset('CSS/button.css') }}">

    <h1>Ticket Details</h1>

    <div class="ticket-details">
        <p><strong>ID:</strong> {{ $ticket->id }}</p>
        <p><strong>Nama LOP:</strong> {{ $ticket->NAMA_LOP }}</p>
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
            <div id="evidencePreview" class=""></div>
        @endif

        @if($ticket->surat_pihak_ketiga_path)
            <p><strong>Surat Pihak Ketiga:</strong></p>
            <div id="suratPreview" class=""></div>
        @endif
    </div>

    <div class="actions">
        <a href="{{ route('ticket.edit', $ticket) }}" class="ferari02">Edit</a>
        <a href="{{ route('ticket.dashboard') }}" class="toyota">Back to Dashboard</a>
    </div>
@endsection

@section('modal')
    <div id="fileModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <div id="modalContent"></div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if($ticket->evidence_path)
            displayFile('{{ asset('storage/' . $ticket->evidence_path) }}', 'evidencePreview', '{{ pathinfo($ticket->evidence_path, PATHINFO_EXTENSION) }}');
        @endif

        @if($ticket->surat_pihak_ketiga_path)
            displayFile('{{ asset('storage/' . $ticket->surat_pihak_ketiga_path) }}', 'suratPreview', '{{ pathinfo($ticket->surat_pihak_ketiga_path, PATHINFO_EXTENSION) }}');
        @endif
    });

    function displayFile(src, containerId, fileType) {
        const container = document.getElementById(containerId);
        fileType = fileType.toLowerCase();

        const imageTypes = ['jpg', 'jpeg', 'png', 'gif', 'svg'];
        const documentTypes = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt'];

        if (imageTypes.includes(fileType)) {
            container.innerHTML = `<img src="${src}" alt="File Preview" onclick="openModal('${src}', 'image')">`;
        } else if (documentTypes.includes(fileType)) {
            container.innerHTML = `<a href="#" class="button2" onclick="openModal('${src}', 'file')">View File</a>`;
        } else {
            container.innerHTML = `<p>File type not supported for preview. <a href="${src}" target="_blank">Download file</a></p>`;
        }
    }

    function openModal(src, type) {
        var modal = document.getElementById("fileModal");
        var modalContent = document.getElementById("modalContent");
        modal.style.display = "block";
        
        if (type === 'image') {
            modalContent.innerHTML = `<img src="${src}" style="max-width:100%; max-height:80vh;">`;
        } else {
            modalContent.innerHTML = `<iframe src="${src}" style="width:100%; height:80vh; border:none;"></iframe>`;
        }
    }

    function closeModal() {
        var modal = document.getElementById("fileModal");
        modal.style.display = "none";
    }
</script>
@endsection

<style>

.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.9);
}

.modal-content {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 700px;
}

.close {
    position: absolute;
    top: 15px;
    right: 35px;
    color: #f1f1f1;
    font-size: 40px;
    font-weight: bold;
    transition: 0.3s;
}

.close:hover,
.close:focus {
    color: #bbb;
    text-decoration: none;
    cursor: pointer;
}
</style>