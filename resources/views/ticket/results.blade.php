@extends('layouts.app')

@section('title', 'Ticket Results')

@section('content')
    <h1>Ticket Results</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>NAMA LOP</th>
                <th>Judul Pengajuan</th>
                <th>Jenis QE</th>
                <th>STO</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $ticket)
                <tr>
                    <td class="expandable" data-column="id">{{ $ticket->id }}</td>
                    <td class="expandable" data-column="nama_lop">{{ $ticket->NAMA_LOP }}</td>
                    <td class="expandable" data-column="judul_pengajuan">{{ $ticket->judul_pengajuan }}</td>
                    <td class="expandable" data-column="jenis_qe">{{ $ticket->jenis_QE }}</td>
                    <td class="expandable" data-column="sto">{{ $ticket->sto }}</td>
                    <td>
                        <a href="{{ route('ticket.show', $ticket) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('ticket.edit', $ticket) }}" class="btn btn-sm btn-warning">Edit</a>
                    </td>
                </tr>
                <tr class="expandable-row" id="expandable-{{ $ticket->id }}" style="display: none;">
                        <td colspan="10">
                            <div class="expanded-content"></div>
                        </td>
                    </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('ticket.create') }}" class="btn btn-primary">Create New Ticket</a>
@endsection