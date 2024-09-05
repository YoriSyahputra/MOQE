<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f0f4ff, #d6eaff);
            margin: 0;
            padding: 0;
            color: #333;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        .container {
            flex: 1;
            display: flex;
            flex-direction: column;
            margin-top: 20px;
        }

        .header {
            background-color: #5c5656;
            color: white;
            padding: 30px;
            text-align: left;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            margin-bottom: 20px;
            border-radius: 0;
        }

        .header h1 {
            margin: 0;
            font-size: 2.5rem;
            font-weight: bold;
            flex: 1;
        }

        .profile {
            display: flex;
            align-items: center;
            margin-left: auto;
            margin-right: 50px;
        }

        .profile img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
        }

        .profile a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            background-color: #333;
            padding: 10px 20px;
            border-radius: 30px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .profile a:hover {
            background-color: #555;
        }

        .main-content {
            flex: 1;
            display: flex;
            margin-top: 20px;
        }

        .sidebar {
            background-color: #5c5656;
            padding: 20px;
            color: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 250px;
            flex-shrink: 0;
            height: calc(100vh - 160px);
            position: sticky;
            top: 100px;
            border-radius: 10px;
        }

        .sidebar h2 {
            margin-top: 0;
            font-size: 1.8rem;
            font-weight: bold;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .sidebar ul li {
            margin: 20px 0;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            background-color: #444;
            padding: 10px 15px;
            border-radius: 30px;
            display: block;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .sidebar ul li a:hover {
            background-color: #555;
        }

        .content {
            flex: 1;
            margin-left: 20px;
            background-color: white;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow-y: auto;
        }

        .content h1 {
            margin-top: 0;
            font-size: 2rem;
            color: #5c5656;
            font-weight: 700;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .content table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .content table th, .content table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .content table th {
            background-color: #f7f7f7;
            color: #333;
            font-weight: bold;
        }

        .content table tr:hover {
            background-color: #f1f1f1;
        }

        .content .alert {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
            border-radius: 10px;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: static;
                width: 100%;
                max-width: 100%;
                height: auto;
                margin-bottom: 20px;
            }

            .content {
                margin-left: 0;
            }
        }

        .thumbnail {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
        }

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
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Monitoring QE</h1>
            <div class="profile">
                <img src="https://via.placeholder.com/50" alt="Profile Picture">
                <a href="{{ route('ticket.index') }}" class="btn back-button">Logout</a>
            </div>
        </div>

        <div class="main-content">
            <div class="sidebar">
                <h2>Menu</h2>
                <ul>  
                    <li><a href="{{ route('ticket.create') }}">Create Ticket</a></li>
                    <li><a href="#">Dashboard</a></li>
                    <li><a href="#">Reports</a></li>
                    <li><a href="#">Settings</a></li>
                </ul>
            </div>

            <div class="content">
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
                            <th>Judul Pengajuan</th>
                            <th>Jenis QE</th>
                            <th>Titik Koordinasi</th>
                            <th>Tingkat Urgensi</th>
                            <th>Pelapor</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Evidence</th>
                            <th>Surat Pihak Ketiga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tickets as $ticket)
                            <tr>
                                <td>{{ $ticket->id }}</td>
                                <td>{{ $ticket->judul_pengajuan }}</td>
                                <td>{{ $ticket->jenis_QE }}</td>
                                <td>{{ $ticket->titik_kordinasi }}</td>
                                <td>{{ $ticket->tingkat_urgensi }}</td>
                                <td>{{ $ticket->pelapor }}</td>
                                <td>{{ $ticket->tanggal_pengajuan }}</td>
                                <td>
                                    @if($ticket->evidence_path)
                                        @if(in_array(pathinfo($ticket->evidence_path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                            <img src="{{ asset('storage/' . $ticket->evidence_path) }}" alt="Evidence" class="thumbnail" onclick="openModal('{{ asset('storage/' . $ticket->evidence_path) }}')">
                                        @else
                                            <a href="{{ asset('storage/' . $ticket->evidence_path) }}" target="_blank">View Evidence</a>
                                        @endif
                                    @else
                                        No evidence
                                    @endif
                                </td>
                                <td>
                                    @if($ticket->surat_pihak_ketiga_path)
                                        @if(in_array(pathinfo($ticket->surat_pihak_ketiga_path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                            <img src="{{ asset('storage/' . $ticket->surat_pihak_ketiga_path) }}" alt="Surat Pihak Ketiga" class="thumbnail" onclick="openModal('{{ asset('storage/' . $ticket->surat_pihak_ketiga_path) }}')">
                                        @else
                                            <a href="{{ asset('storage/' . $ticket->surat_pihak_ketiga_path) }}" target="_blank">View Surat Pihak Ketiga</a>
                                        @endif
                                    @else
                                        No surat pihak ketiga
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="imageModal" class="modal">
    <span class="close">&times;</span>
    <img class="modal-content" id="modalImage">
</div>

    <script>
        function openModal(src) {
            var modal = document.getElementById("imageModal");
            var modalImg = document.getElementById("modalImage");
            modal.style.display = "block";
            modalImg.src = src;
}

        var span = document.getElementsByClassName("close")[0];
            span.onclick = function() { 
            document.getElementById("imageModal").style.display = "none";
        }
</script>
</body>
</html>

in my structur file like resources/views/tickets/dashboard.blade.php,reports.blade.php,create.blade.php and earlier i built resources/views/layouts/layout.blade.php