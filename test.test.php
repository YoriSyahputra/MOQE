<?php
// app/Http/Controllers/TicketController.php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'judul_pengajuan' => 'required|string|max:255',
            'jenis_QE' => 'required|string|in:QE Type 1,QE Type 2,QE Type 3',
            'detail_QE' => 'required|string',
            'titik_kordinasi' => ['required', 'regex:/^-?\d{1,2}\.\d{1,10},\s*-?\d{1,3}\.\d{1,10}$/'],
            'tingkat_urgensi' => 'required|in:1,2,3',
            'pelapor' => 'required|string|max:255',
            'tanggal_pengajuan' => 'required|date|before_or_equal:today',
            'keterangan' => 'nullable|string',
            'evidence_path' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
            'surat_pihak_ketiga_path' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
        ]);

        // Handle file uploads
        if ($request->hasFile('evidence_path')) {
            $evidencePath = $request->file('evidence_path')->store('evidences', 'public');
            $validatedData['evidence_path'] = $evidencePath;
        }

        if ($request->hasFile('surat_pihak_ketiga_path')) {
            $suratPath = $request->file('surat_pihak_ketiga_path')->store('surat_pihak_ketiga', 'public');
            $validatedData['surat_pihak_ketiga_path'] = $suratPath;
        }

        try {
            // Create the ticket
            $ticket = Ticket::create($validatedData);
            return redirect()->route('ticket.index')->with('success', 'Ticket berhasil dibuat.');
        } catch (\Exception $e) {
            Log::error('Error creating ticket: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat membuat ticket. Silakan coba lagi.']);
        }
    }
}

// app/Models/Ticket.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul_pengajuan',
        'jenis_QE',
        'detail_QE',
        'titik_kordinasi',
        'tingkat_urgensi',
        'pelapor',
        'tanggal_pengajuan',
        'keterangan',
        'evidence_path',
        'surat_pihak_ketiga_path',
    ];
}

// routes/web.php

use App\Http\Controllers\TicketController;

Route::post('/ticket-store', [TicketController::class, 'store'])->name('ticket.store');

// resources/views/ticket/create.blade.php (form part)

<form action="{{ route('ticket.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <!-- Your form fields here -->
    <button type="submit" class="btn btn-primary">Submit Ticket</button>
</form>
?>









// namespace App\Http\Controllers;

// use App\Models\Ticket;
// use Illuminate\Http\Request;

// class TicketController extends Controller
// {
//     // Show the list of tickets
//     public function index()
//     {
//         $tickets = Ticket::all();
//         return view('ticket.index', compact('tickets'));
//     }

//     // Show the create ticket form
//     public function create()
//     {
//         return view('ticket.create');
//     }

//     // Store a newly created ticket in the database
//     public function store(Request $request)
//     {
//         // Validate incoming request data
//         $request->validate([
//             'judul_pengajuan' => 'required|string|max:255',
//             'jenis_QE' => 'required|string|in:QE Type 1,QE Type 2,QE Type 3',
//             'detail_QE' => 'required|string',
//             'titik_kordinasi' => ['required', 'regex:/^-?\d{1,2}\.\d{1,10},\s*-?\d{1,3}\.\d{1,10}$/'],
//             'tingkat_urgensi' => 'required|in:1,2,3',
//             'pelapor' => 'required|string|max:255',
//             'tanggal_pengajuan' => 'required|date|before_or_equal:today',
//             'keterangan' => 'nullable|string',
//             'evidence_path' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
//             'surat_pihak_ketiga_path' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
//         ]);

//         // Create a new ticket with validated data
//         Ticket::create($request->all());

//         // Redirect to the dashboard with a success message
//         return redirect()->route('ticket.dashboard')
//                          ->with('success', 'Ticket created successfully.');
//     }

//     // Show the dashboard
//     public function dashboard()
//     {
//         return view('ticket.dashboard');
//     }
// }

// namespace App\Http\Controllers;

// use App\Models\Ticket;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Storage;

// class TicketController extends Controller
// {
//     // Display a list of all tickets
//     public function index()
//     {
//         $tickets = Ticket::all();
//         return view('ticket.index', compact('tickets'));
//     }

//     // Show the form to create a new ticket
//     public function create()
//     {
//         return view('ticket.create');
//     }

//     // Store a newly created ticket in the database
//     public function store(Request $request)
//     {
//         // Validate the form data
//         $request->validate([
//             'judul_pengajuan' => 'required|string|max:255',
//             'jenis_QE' => 'required|string|in:QE Type 1,QE Type 2,QE Type 3',
//             'detail_QE' => 'required|string',
//             'titik_kordinasi' => ['required', 'regex:/^-?\d{1,2}\.\d{1,10},\s*-?\d{1,3}\.\d{1,10}$/'],
//             'tingkat_urgensi' => 'required|in:1,2,3',
//             'pelapor' => 'required|string|max:255',
//             'tanggal_pengajuan' => 'required|date|before_or_equal:today',
//             'keterangan' => 'nullable|string',
//             'evidence_path' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
//             'surat_pihak_ketiga_path' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
//         ]);

//         // Handle file uploads
//         $evidencePath = $request->file('evidence_path')->store('evidence');
//         $suratPihakKetigaPath = $request->file('surat_pihak_ketiga_path')->store('surat_pihak_ketiga');

//         // Create a new ticket record in the database
//         $ticket = new Ticket();
//         $ticket->judul_pengajuan = $request->judul_pengajuan;
//         $ticket->jenis_QE = $request->jenis_QE;
//         $ticket->detail_QE = $request->detail_QE;
//         $ticket->titik_kordinasi = $request->titik_kordinasi;
//         $ticket->tingkat_urgensi = $request->tingkat_urgensi;
//         $ticket->pelapor = $request->pelapor;
//         $ticket->tanggal_pengajuan = $request->tanggal_pengajuan;
//         $ticket->keterangan = $request->keterangan;
//         $ticket->evidence_path = $evidencePath;
//         $ticket->surat_pihak_ketiga_path = $suratPihakKetigaPath;
//         $ticket->save();

//         // Redirect to the dashboard with a success message
//         return redirect()->route('ticket.dashboard')
//                          ->with('success', 'Ticket created successfully.');
//     }

//     // Display the dashboard (Assuming this method already exists)
//     public function dashboard()
//     {
//         // Logic to show dashboard
//         return view('ticket.dashboard');
//     }
// }


// // namespace App\Http\Controllers;

// // use App\Models\Ticket;
// // use Illuminate\Http\Request;
// // use Illuminate\Support\Facades\Storage;

// // class TicketController extends Controller
// // {
// //     public function index()
// //     {
// //         $tickets = Ticket::all();
// //         return view('ticket.index', compact('tickets'));
// //     }

// //     public function create()
// //     {
// //         return view('ticket.create');
// //     }

// //     public function store(Request $request)
// //     {
// //         $request->validate([
// //             'judul_pengajuan' => 'required|string|max:255',
// //             'jenis_QE' => 'required|string|in:QE Type 1,QE Type 2,QE Type 3',
// //             'detail_QE' => 'required|string',
// //             'titik_kordinasi' => ['required', 'regex:/^-?\d{1,2}\.\d{1,10},\s*-?\d{1,3}\.\d{1,10}$/'],
// //             'tingkat_urgensi' => 'required|in:1,2,3',
// //             'pelapor' => 'required|string|max:255',
// //             'tanggal_pengajuan' => 'required|date|before_or_equal:today',
// //             'keterangan' => 'nullable|string',
// //             'evidence_path' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
// //             'surat_pihak_ketiga_path' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
// //         ]);
// //         Ticket::create($request->all());
     

// //         return redirect()->route('ticket.dashboard')
// //                         ->with('success','Student created successfully.');
// //     }

// //     }

// // //     public function store(Request $request)
// // //     {
// // //         $validatedData = $request->validate([
// // //             'judul_pengajuan' => 'required|string|max:255',
// // //             'jenis_QE' => 'required|string|in:QE Type 1,QE Type 2,QE Type 3',
// // //             'detail_QE' => 'required|string',
// // //             'titik_kordinasi' => ['required', 'regex:/^-?\d{1,2}\.\d{1,10},\s*-?\d{1,3}\.\d{1,10}$/'],
// // //             'tingkat_urgensi' => 'required|in:1,2,3',
// // //             'pelapor' => 'required|string|max:255',
// // //             'tanggal_pengajuan' => 'required|date|before_or_equal:today',
// // //             'keterangan' => 'nullable|string',
// // //             'evidence_path' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
// // //             'surat_pihak_ketiga_path' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
// // //         ]);

// // //         if ($request->hasFile('evidence_path')) {
// // //             $validatedData['evidence_path'] = $request->file('evidence_path')->store('evidences', 'public');
// // //         }

// // //         if ($request->hasFile('surat_pihak_ketiga_path')) {
// // //             $validatedData['surat_pihak_ketiga_path'] = $request->file('surat_pihak_ketiga_path')->store('surat_pihak_ketiga', 'public');
// // //         }

// // //         Ticket::create($validatedData);

        
// // //         return redirect()->route('ticket.index')->with('success', 'Ticket created successfully.');
// // //     }

// // //     // Add other methods like show, edit, update, destroy as needed
// // // 











// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\TicketController;

// Route::get('/', function () {
//     return view('welcome');
// });


// //store
// route::post('/tickets', [TicketController::class, 'store'])->name('ticket.store');
// Route::post('index', [App\Http\Controllers\TicketController::class, 'store'])->name('ticket.store');
// //ned5

// //index
// route::get('/ticket', [TicketController::class, 'index'])->name('ticket.index');
// //ned4

// //create
// route::get('/tickets/create', [TicketController::class, 'create'])->name('ticket.create');
// route::post('/ticket/create', [TicketController::class, 'create'])->name('ticket.create');
// //ned2

// //mandal
// route::get('/ticket/index', [TicketController::class, 'mandal'])->name('ticket.mandal');
// route::post('/index', [TicketController::class, 'mandal'])->name('ticket.mandal');
// //ned3

// //dashbaord
// route::post('/dashboard', [TicketController::class, 'dashboard'])->name('ticket.dashboard');
// route::get('/dashboard', [TicketController::class, 'dashboard'])->name('ticket.dashboard');
// //ned1

// //update

// Route::post('/index','TicketController@update'); 
// //ned6


// //test
// // Route::get('/tickets', [TicketController::class, 'index'])->name('ticket.index');
// // Route::get('/tickets/create', [TicketController::class, 'create'])->name('ticket.create');

// // Route::post('/tickets', [TicketController::class, 'store'])->name('ticket.store');
// // Route::put('/ticket-update/{id}', [TicketController::class, 'update'])->name('ticket.update');

// // Route::get('/ticket/mandal', [TicketController::class, 'mandal'])->name('ticket.mandal');


// // Route::get('/dashboard', [TicketController::class,'ticket.dashboard']);

// //end





i want after i submiting the form , in dashboard there are form that i was submiting , and i want after i submiting the form ,the form was putting into the database or laragon.mysql and the form in inside the dashboard . and iwant after i submiting, i back to the dashboard and the form we submiting appear inside the dashboard

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        /* Your existing styles here */
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Dashboard</h1>
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
                            <th>Actions</th>
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