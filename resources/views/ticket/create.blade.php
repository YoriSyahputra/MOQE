@extends('layouts.app')

@section('title', 'MonitoringQE')

@section('content')
<link rel="stylesheet" href="{{asset ('CSS/style.css')}}">

    <h1>Create New Ticket</h1>
   
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    
    <form action="{{ route('ticket.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
        <div class="form-group mb-3">
            <label for="judul_pengajuan">Nama LOP</label>
            <input type="text" class="form-control" id="judul_pengajuan" name="judul_pengajuan" required>
        </div>
        <div class="form-group mb-3">
            <label for="jenis_QE">Jenis QE</label>
            <select class="form-control" id="jenis_QE" name="jenis_QE" required>
                <option value="QE RELOKASI">QE RELOKASI</option>
                <option value="QE PREVENTIF">QE PREVENTIF</option>
            </select>
        </div>
        <div class="form-group mb-3">
            <label for="sto">PILIH STO</label>
            <select class="form-control" id="sto" name="sto" required>
                <option value="BJA">BJA</option>
                <option value="CMI">CMI</option>
                <option value="CWD">CWD</option>
                <option value="MJY">MJY</option>
                <option value="PDL">PDL</option>
                <option value="PNL">PNL</option>
                <option value="SOR">SOR</option>
                <option value="RJW">RJW</option>
                <option value="CCL">CCL</option>
                <option value="BTJ">BTJ</option>
                <option value="CKW">CKW</option>
                <option value="CLL">CLL</option>
                <option value="CPT">CPT</option>
                <option value="CSA">CSA</option>
                <option value="GNH">GNH</option>
                <option value="LEM">LEM</option>
                <option value="RCK">RCK</option>
            </select>
        </div>
        
        <input type="hidden" id="NAMA_LOP" name="NAMA_LOP">

        <div class="form-group mb-3">
            <label for="alamat">Alamat Lengkap</label>
            <textarea class="form-control" id="alamat" name="alamat" required></textarea>
        </div>
        <div class="form-group mb-3">
            <label for="kebutuhan_material">Kebutuhan Material</label>
            <textarea class="form-control" id="kebutuhan_material" name="kebutuhan_material" required></textarea>
        </div>
        <div class="form-group mb-3">
            <label for="progress">Progress</label>
            <select class="form-control" id="progress" name="progress" required>
                <option value="Need Survey">Need Survey</option>
                <option value="Need Create BoQ">Need Create BoQ</option>
                <option value="Done BoQ">Done BoQ</option>
                <option value="Done NDE Pengajuan">Done NDE Pengajuan</option>
                <option value="Done Input IHLD">Done Input IHLD</option>
                <option value="SPMK">SPMK</option>
                <option value="On progres lapangan">On progres lapangan</option>
                <option value="Closed">Closed</option>
            </select>
        </div>
        <div class="form-group mb-3">
            <label for="detail_QE">Detail QE</label>
            <select class="form-control" id="detail_QE" name="detail_QE" required>
                <option value="KABEL MENJUNTAI">KABEL MENJUNTAI</option>
                <option value="RELOKASI">RELOKASI</option>
                <option value="TIANG MIRING/TIANG ROBOH/TIANG KEROPOS">TIANG MIRING/TIANG ROBOH/TIANG KEROPOS</option>
            </select>
        </div>
        <div class="form-group mb-3">
            <label for="titik_kordinasi">Titik Koordinasi</label>
            <input type="text" class="form-control" id="titik_kordinasi" name="titik_kordinasi">
        </div>
        <div class="form-group mb-3">
            <label for="tingkat_urgensi">Tingkat Urgensi</label>
            <select class="form-control" id="tingkat_urgensi" name="tingkat_urgensi" required>
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>
            </select>
        </div>
        <div class="form-group mb-3">
            <label for="pelapor">Pelapor</label>
            <input type="text" class="form-control" id="pelapor" name="pelapor" required>
        </div>
        <div class="form-group mb-3">
            <label for="tanggal_pengajuan">Tanggal Pengajuan</label>
            <input type="date" class="form-control" id="tanggal_pengajuan" name="tanggal_pengajuan" required>
        </div>
        <div class="form-group mb-3">
            <label for="keterangan">Keterangan</label>
            <textarea class="form-control" id="keterangan" name="keterangan"></textarea>
        </div>
        <div class="form-group mb-3">
            <label for="evidence_path">Evidence</label>
            <input type="file" class="form-control-file" id="evidence_path" name="evidence_path" onchange="previewFile('evidence_path', 'evidencePreview')">
            <div id="evidencePreview"></div>
        </div>
        <div class="form-group mb-3">
            <label for="surat_pihak_ketiga_path">Surat Pihak Ketiga</label>
            <input type="file" class="form-control-file" id="surat_pihak_ketiga_path" name="surat_pihak_ketiga_path" accept=".jpg,.jpeg,.png,.gif,.svg,.pdf,.doc,.docx,.xls,.xlsx,.txt" onchange="previewFile('surat_pihak_ketiga_path', 'suratPreview')">
            <small class="form-text text-muted">Accepted file types: jpg, jpeg, png, gif, svg, pdf, doc, docx, xls, xlsx, txt</small>
            <div id="suratPreview"></div>
        </div>
        
        <button type="submit" class="btn btn-primary">Submit</button>
</form>

<div class="toast">
    <div class="toast-content">
        <i class="fas fa-solid fa-check check"></i>
        <div class="message">

        </div>
    </div>
    <i class="fa-solid fa-xmark close"></i>
    <div class="progress"></div>
</div>

        
@endsection

@section('scripts')
<script>
function previewFile(inputId, previewId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    const file = input.files[0];

    if (file) {
        const fileType = file.type;
        const fileName = file.name;
        const fileUrl = URL.createObjectURL(file);

        if (fileType.startsWith('image/')) {
            preview.innerHTML = `<img src="${fileUrl}" style="max-width: 200px; max-height: 200px;">`;
        } else if (fileType === 'application/pdf' || 
                   fileType === 'application/msword' || 
                   fileType === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ||
                   fileType === 'application/vnd.ms-excel' ||
                   fileType === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' ||
                   fileType === 'text/plain') {
            const googleDocsUrl = `https://docs.google.com/viewer?url=${encodeURIComponent(fileUrl)}&embedded=true`;
            preview.innerHTML = `
                <p>File selected: ${fileName}</p>
                <iframe src="${googleDocsUrl}" width="100%" height="400px" frameborder="0"></iframe>
            `;
        } else {
            preview.innerHTML = `<p>File selected: ${fileName}</p>`;
        }
    } else {
        preview.innerHTML = '';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('ticketForm');
    form.addEventListener('submit', (event) => {
        event.preventDefault();
        
        fetch(form.action, {
            method: form.method,
            body: new FormData(form)
        }).then(response => {
            if (response.ok) {
                showSuccessMessage();
                setTimeout(() => {
                    window.location.href = "{{ route('ticket.dashboard') }}";
                }, 3000);
            }
        });
    });
});

function showSuccessMessage() {
    const message = document.createElement('div');
    message.className = 'success-message';
    message.textContent = 'Well done! You successfully created this important ticket. Redirecting to dashboard...';
    
    document.body.appendChild(message);
    
    setTimeout(() => {
        document.body.removeChild(message);
    }, 3000);
}
</script>
@endsection