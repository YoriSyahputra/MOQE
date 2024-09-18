@extends('layouts.app')

@section('title', 'Edit Ticket - MonitoringQE')

@section('content')
<link rel="stylesheet" href="{{ asset('CSS/style.css') }}">

<div class="container">
    <h1>Edit Ticket</h1>
   
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    
    <form action="{{ route('ticket.update', $ticket->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
        <div class="form-group mb-3">
            <label for="judul_pengajuan">Nama LOP</label>
            <input type="text" class="form-control" id="judul_pengajuan" name="judul_pengajuan" value="{{ old('judul_pengajuan', $ticket->judul_pengajuan) }}" required>
        </div>
        <div class="form-group mb-3">
            <label for="jenis_QE">Jenis QE</label>
            <select class="form-control" id="jenis_QE" name="jenis_QE" required>
                <option value="QE RELOKASI" {{ old('jenis_QE', $ticket->jenis_QE) == 'QE RELOKASI' ? 'selected' : '' }}>QE RELOKASI</option>
                <option value="QE PREVENTIF" {{ old('jenis_QE', $ticket->jenis_QE) == 'QE PREVENTIF' ? 'selected' : '' }}>QE PREVENTIF</option>
            </select>
        </div>
        <div class="form-group mb-3">
            <label for="sto">PILIH STO</label>
            <select class="form-control" id="sto" name="sto" required>
                @foreach(['BJA', 'CMI', 'CWD', 'MJY', 'PDL', 'PNL', 'SOR', 'RJW', 'CCL', 'BTJ', 'CKW', 'CLL', 'CPT', 'CSA', 'GNH', 'LEM', 'RCK'] as $stoOption)
                    <option value="{{ $stoOption }}" {{ old('sto', $ticket->sto) == $stoOption ? 'selected' : '' }}>{{ $stoOption }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group mb-3">
            <label for="nomer_ticket_insera">Nomer Ticket Insera</label>
            <input type="text" class="form-control" id="nomer_ticket_insera" name="nomer_ticket_insera" value="{{ old('nomer_ticket_insera', $ticket->nomer_ticket_insera) }}">
        </div>
        <div class="form-group mb-3">
            <label for="alamat">Alamat Lengkap</label>
            <textarea class="form-control" id="alamat" name="alamat" required>{{ old('alamat', $ticket->alamat) }}</textarea>
        </div>
        <div class="form-group mb-3">
            <label for="kebutuhan_material">Kebutuhan Material</label>
            <textarea class="form-control" id="kebutuhan_material" name="kebutuhan_material" required>{{ old('kebutuhan_material', $ticket->kebutuhan_material) }}</textarea>
        </div>
        <div class="form-group mb-3">
            <label for="progress">Progress</label>
            <select class="form-control" id="progress" name="progress" required>
                @foreach(['Need Survey', 'Need Create BoQ', 'Done BoQ', 'Done NDE Pengajuan', 'Done Input IHLD', 'SPMK', 'On progres lapangan', 'Closed'] as $progressOption)
                    <option value="{{ $progressOption }}" {{ old('progress', $ticket->progress) == $progressOption ? 'selected' : '' }}>{{ $progressOption }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group mb-3">
            <label for="detail_QE">Detail QE</label>
            <select class="form-control" id="detail_QE" name="detail_QE" required>
                <option value="KABEL MENJUNTAI" {{ old('detail_QE', $ticket->detail_QE) == 'KABEL MENJUNTAI' ? 'selected' : '' }}>KABEL MENJUNTAI</option>
                <option value="RELOKASI" {{ old('detail_QE', $ticket->detail_QE) == 'RELOKASI' ? 'selected' : '' }}>RELOKASI</option>
                <option value="TIANG MIRING/TIANG ROBOH/TIANG KEROPOS" {{ old('detail_QE', $ticket->detail_QE) == 'TIANG MIRING/TIANG ROBOH/TIANG KEROPOS' ? 'selected' : '' }}>TIANG MIRING/TIANG ROBOH/TIANG KEROPOS</option>
            </select>
        </div>
        <div class="form-group mb-3">
            <label for="titik_kordinasi">Titik Koordinat</label>
            <input type="text" class="form-control" id="titik_kordinasi" name="titik_kordinasi" value="{{ old('titik_kordinasi', $ticket->titik_kordinasi) }}">
        </div>
        <div class="form-group mb-3">
            <label for="tingkat_urgensi">Tingkat Urgensi</label>
            <select class="form-control" id="tingkat_urgensi" name="tingkat_urgensi" required>
                <option value="Low" {{ old('tingkat_urgensi', $ticket->tingkat_urgensi) == 'Low' ? 'selected' : '' }}>Low</option>
                <option value="Medium" {{ old('tingkat_urgensi', $ticket->tingkat_urgensi) == 'Medium' ? 'selected' : '' }}>Medium</option>
                <option value="High" {{ old('tingkat_urgensi', $ticket->tingkat_urgensi) == 'High' ? 'selected' : '' }}>High</option>
            </select>
        </div>
        <div class="form-group mb-3">
            <label for="pelapor">Pelapor</label>
            <input type="text" class="form-control" id="pelapor" name="pelapor" value="{{ old('pelapor', $ticket->pelapor) }}" required>
        </div>
        <div class="form-group mb-3">
            <label for="tanggal_pengajuan">Tanggal Pengajuan</label>
            <input type="date" class="form-control" id="tanggal_pengajuan" name="tanggal_pengajuan" value="{{ old('tanggal_pengajuan', $ticket->tanggal_pengajuan) }}" required>
        </div>
        <div class="form-group mb-3">
            <label for="keterangan">Keterangan</label>
            <textarea class="form-control" id="keterangan" name="keterangan">{{ old('keterangan', $ticket->keterangan) }}</textarea>
        </div>
        <div class="form-group mb-3">
            <label for="evidence_path">Evidence</label>
            <input type="file" class="form-control-file" id="evidence_path" name="evidence_path">
            @if($ticket->evidence_path)
                <p>Current file: {{ basename($ticket->evidence_path) }}</p>
            @endif
        </div>
        <div class="form-group mb-3">
            <label for="surat_pihak_ketiga_path">Surat Pihak Ketiga</label>
            <input type="file" class="form-control-file" id="surat_pihak_ketiga_path" name="surat_pihak_ketiga_path" accept=".jpg,.jpeg,.png,.gif,.svg,.pdf,.doc,.docx,.xls,.xlsx,.txt">
            <small class="form-text text-muted">Accepted file types: jpg, jpeg, png, gif, svg, pdf, doc, docx, xls, xlsx, txt</small>
            @if($ticket->surat_pihak_ketiga_path)
                <p>Current file: {{ basename($ticket->surat_pihak_ketiga_path) }}</p>
            @endif
        </div>
        <button type="submit" class="btn btn-primary">Update Ticket</button>
    </form>
</div>
@endsection