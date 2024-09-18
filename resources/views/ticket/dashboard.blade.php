@extends('layouts.app')

@section('title', 'MonitoringQE')

@section('content')
<link rel="stylesheet" href="{{ asset('CSS/button.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>Ticket Dashboard</h1>
        </div>
        <div class="col-md-6">
          
            <div id="searchSuggestions" class="dropdown-menu" style="display:none;"></div>
        </div>
    </div>

    <div class="header-section"
        <div class="apple-filter">
            <select id="columnFilter" class="form-control">
                <option value="">Filter by column...</option>
                <option value="0">ID</option>
                <option value="1">STO</option>
                <option value="2">Nama LOP</option>
                <option value="3">Jenis QE</option>
                <option value="4">Tingkat Urgensi</option>
                <option value="5">Pelapor</option>
                <option value="6">Tanggal Pengajuan</option>
            </select>
            <input type="text" id="filterValue" class="form-control" class="search-bar" placeholder="Filter value...">
        </div>
        <div class="d-flex justify-content-end align-items-center">
               
               <div class="huawei-az" id="azFilter">A-Z</div>
           </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>  
    @endif

    <div class="table-responsive">
        <table id="ticketTable" class="table table-bordered table-striped">
            <div class="table-header">
                <thead>
                    <tr>
                        <th>ID <i class="fas fa-sort"></i></th>
                        <th>STO <i class="fas fa-sort"></i></th>
                        <th>Nama LOP <i class="fas fa-sort"></i></th>
                        <th>Jenis QE <i class="fas fa-sort"></i></th>
                        <th>Tingkat Urgensi <i class="fas fa-sort"></i></th>
                        <th>Pelapor <i class="fas fa-sort"></i></th>
                        <th>Tanggal Pengajuan <i class="fas fa-sort"></i></th>
                        <th>Evidence</th>
                        <th>Surat Pihak Ketiga</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                </div>
            <tbody>
                @foreach($tickets as $ticket)
                    <tr class="ticket-row" data-href="{{ route('ticket.show', $ticket) }}">
                        <td>{{ $ticket->id }}</td>
                        <td>{{ $ticket->sto }}</td>
                        <td>{{ $ticket->judul_pengajuan }}</td>
                        <td>{{ $ticket->jenis_QE }}</td>
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
                            Tidak ada surat pihak ketiga
                        @endif
                    </td>
                        <td>
                            <a href="{{ route('ticket.edit', $ticket) }}" class="ferari">Edit</a>
                            <form action="{{ route('ticket.destroy', $ticket) }}" method="POST" style="display: inline;" onsubmit="confirmDelete(event, this)">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bmw">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('modal')
    <div id="fileModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <div id="modalContent"></div>
    </div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    function confirmDelete(event, form) {
  event.preventDefault();
  
  const modal = document.createElement('div');
  modal.innerHTML = `
    <div class="confirm-modal">
      <div class="modal-content">
        <h2>Confirm</h2>
        <p>Are you sure you want to permanently delete this ticket?</p>
        <div class="button-group">
          <button class="btn-yes">Yes, Delete!</button>
          <button class="btn-cancel">Cancel</button>
        </div>
      </div>
    </div>
  `;
  
  document.body.appendChild(modal);
  
  modal.querySelector('.btn-yes').addEventListener('click', () => {
    form.submit();
  });
  
  modal.querySelector('.btn-cancel').addEventListener('click', () => {
    document.body.removeChild(modal);
  });
}

document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('form');
  form.addEventListener('submit', (event) => {
    event.preventDefault();
    
    // Submit the form
    fetch(form.action, {
      method: form.method,
      body: new FormData(form)
    }).then(response => {
      if (response.ok) {
        showSuccessMessage();
        form.reset();
      }
    });
  });
});

function showSuccessMessage() {
  const message = document.createElement('div');
  message.className = 'success-message';
  message.textContent = 'Well done! You successfully created this important ticket.';
  
  document.body.appendChild(message);
  
  setTimeout(() => {
    document.body.removeChild(message);
  }, 3000);
}

$(document).ready(function() {
    // Flexible UI
    $(window).resize(function() {
        adjustTableResponsiveness();
    });

    function adjustTableResponsiveness() {
        var windowWidth = $(window).width();
        if (windowWidth < 768) {
            $('#ticketTable').addClass('table-sm');
        } else {
            $('#ticketTable').removeClass('table-sm');
        }
    }

    // Search functionality with dropdown
    $('#searchInput').on('input', function() {
        var searchTerm = $(this).val().toLowerCase();
        var suggestions = [];

        if (searchTerm.length > 0) {
            $('#ticketTable tbody tr').each(function() {
                var rowText = $(this).text().toLowerCase();
                if (rowText.indexOf(searchTerm) !== -1) {
                    suggestions.push($(this).find('td:first').text());
                }
            });

            displaySuggestions(suggestions);
        } else {
            $('#searchSuggestions').hide();
        }
    });

    function displaySuggestions(suggestions) {
        var suggestionHtml = '';
        suggestions.forEach(function(suggestion) {
            suggestionHtml += '<a class="dropdown-item" href="#">' + suggestion + '</a>';
        });

        $('#searchSuggestions').html(suggestionHtml);
        $('#searchSuggestions').show();
    }

    // Updated A-Z filter functionality
    $('#azFilter').on('click', function() {
        var table = $('#ticketTable');
        var headers = table.find('th');
        var currentSortColumn = parseInt($(this).data('sortColumn') || 0);
        var nextSortColumn = (currentSortColumn + 1) % headers.length;

        var rows = table.find('tr:gt(0)').toArray().sort(comparer(nextSortColumn));
        table.find('tbody').empty().append(rows);

        // Update the sort column for next click
        $(this).data('sortColumn', nextSortColumn);

        // Update the A-Z button text to show which column is being sorted
        $(this).text('A-Z: ' + headers.eq(nextSortColumn).text().trim());
    });

    // Column filter functionality
    $('#columnFilter, #filterValue').on('change keyup', function() {
        var column = $('#columnFilter').val();
        var value = $('#filterValue').val().toLowerCase();

        $('#ticketTable tbody tr').filter(function() {
            var cellText = $(this).find('td').eq(column).text().toLowerCase();
            $(this).toggle(cellText.indexOf(value) > -1);
        });
    });

    // Sorting functionality for column headers
    $('#ticketTable th i.fas.fa-sort').on('click', function() {
        var table = $(this).parents('table').eq(0);
        var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).parent().index()));
        this.asc = !this.asc;
        if (!this.asc) {
            rows = rows.reverse();
        }
        for (var i = 0; i < rows.length; i++) {
            table.append(rows[i]);
        }
    });

    function comparer(index) {
        return function(a, b) {
            var valA = getCellValue(a, index), valB = getCellValue(b, index);
            return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.toString().localeCompare(valB.toString());
        }
    }

    function getCellValue(row, index) {
        return $(row).children('td').eq(index).text().trim();
    }

    // Row click functionality
    $('.ticket-row').on('click', function(e) {
        if (!$(e.target).is('a, button, input')) {
            window.location = $(this).data('href');
        }
    });

    // Modal functionality
    window.openModal = function(src, type) {
        var modal = document.getElementById("fileModal");
        var modalContent = document.getElementById("modalContent");
        modal.style.display = "block";
        
        if (type === 'image') {
            modalContent.innerHTML = `<img src="${src}" style="max-width:100%; max-height:80vh;">`;
        } else {
            modalContent.innerHTML = `<iframe src="${src}" style="width:100%; height:80vh; border:none;"></iframe>`;
        }
    }

    window.closeModal = function() {
        var modal = document.getElementById("fileModal");
        modal.style.display = "none";
    }

    // Initialize
    adjustTableResponsiveness();
});
</script>
@endsection