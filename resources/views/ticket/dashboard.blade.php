@extends('layouts.app')

@section('title', 'MonitoringQE')

@section('content')
<link rel="stylesheet" href="{{asset ('CSS/button.css')}}">

    <h1>Ticket Dashboard</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>  
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>STO</th>
                <th>Judul Pengajuan</th>
                <th>Jenis QE</th>
                <th>Alamat</th>
                <th>Tingkat Urgensi</th>
                <th>Pelapor</th>
                <th>Tanggal Pengajuan</th>
                <th>Evidence</th>
                <th>Surat Pihak Ketiga</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $ticket)
                <tr class="ticket-row" data-href="{{ route('ticket.show', $ticket) }}">
                    <td>{{ $ticket->id }}</td>
                    <td>{{ $ticket->sto }}</td>
                    <td>{{ $ticket->judul_pengajuan }}</td>
                    <td>{{ $ticket->jenis_QE }}</td>
                    <td>{{ $ticket->alamat }}</td>
                    <td>{{ $ticket->tingkat_urgensi }}</td>
                    <td>{{ $ticket->pelapor }}</td>
                    <td>{{ $ticket->tanggal_pengajuan }}</td>
                    <td>
                        @if($ticket->evidence_path)
                            @php
                                $extension = strtolower(pathinfo($ticket->evidence_path, PATHINFO_EXTENSION));
                                $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']);
                            @endphp
                            @if($isImage)
                                <div class="img-container">
                                    <img src="{{ asset('storage/' . $ticket->evidence_path) }}" alt="Evidence" class="thumbnail" onclick="openModal('{{ asset('storage/' . $ticket->evidence_path) }}', 'image')">
                                </div>
                            @else
                                <a href="#" onclick="openModal('{{ asset('storage/' . $ticket->evidence_path) }}', 'file')">View Evidence</a>
                            @endif
                        @else
                            No evidence
                        @endif
                    </td>
                    <td>
                        @if($ticket->surat_pihak_ketiga_path)
                            @php
                                $extension = strtolower(pathinfo($ticket->surat_pihak_ketiga_path, PATHINFO_EXTENSION));
                                $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']);
                            @endphp
                            @if($isImage)
                                <div class="img-container">
                                    <img src="{{ asset('storage/' . $ticket->surat_pihak_ketiga_path) }}" alt="Surat Pihak Ketiga" class="thumbnail" onclick="openModal('{{ asset('storage/' . $ticket->surat_pihak_ketiga_path) }}', 'image')">
                                </div>
                            @else
                                <div class="maning">
                                    <a href="#" onclick="openModal('{{ asset('storage/' . $ticket->surat_pihak_ketiga_path) }}', 'file')" class="tombol">SeeFile</a>
                                </div>  
                            @endif
                        @else
                            No surat pihak ketiga
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('ticket.edit', $ticket) }}" class="ferari">Edit</a>
                        <form action="{{ route('ticket.destroy', $ticket) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bmw" onclick="return confirm('Are you sure you want to delete this ticket?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
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
            const rows = document.querySelectorAll('.ticket-row');
            rows.forEach(row => {
                row.addEventListener('click', function(e) {
                    if (!e.target.closest('a, button, form, img')) {
                        window.location.href = this.dataset.href;
                    }
                });
            });
        });

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