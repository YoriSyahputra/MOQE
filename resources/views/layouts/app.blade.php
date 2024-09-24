<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring QE</title>
    <link rel="stylesheet" href="{{ asset('CSS/dashall.css') }}">
    <link rel="stylesheet" href="{{ asset('CSS/headside.css') }}">
    <link rel="stylesheet" href="{{ asset('CSS/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('CSS/edas.css') }}">
    <link rel="stylesheet" href="{{ asset('CSS/filter.css') }}">
    <link rel="stylesheet" href="{{ asset('CSS/hover.css') }}">
    <link rel="stylesheet" href="{{ asset('CSS/input.css') }}">
    <link rel="stylesheet" href="{{ asset('CSS/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('CSS/phone.css') }}">     
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>
    <div class="main-header">
        <div class="container">
            <div class="header">
                <h1 class="monit">Monitoring QE</h1>
                <div class="profile">
                @if (auth()->user()->profile_photo)
                        <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="profile_photo" class="minta" onclick="openModal('{{ asset('storage/' . auth()->user()->profile_photo) }}', 'image')">
                    @else
                        <img src="{{ asset('storage/default-profile.jpg') }}" alt="Default Profile" class="">
                    @endif      
                    <span class="user-name">{{ auth()->user()->name }}</span>
                    <button onclick="logout()" class="edas">Logout</button>
                </div>
            </div>
        </div>

        <div class="main-content">  
            <div class="sidebar">
                <h2>Menu</h2>

                <ul>  
                    <li class="main-content"><a href="{{ route('ticket.create') }}">Create Ticket</a></li>
                    <li class="main-content"><a href="{{ route('ticket.dashboard') }}">Dashboard</a></li>
                    <li class="main-content"><a href="{{ route('user.settings') }}">Settings</a></li>
                </ul>
            </div>

            <div class="content">
                @yield('content')
            </div>
        </div>
    </div>

    @yield('modal')
    @yield('scripts')

    <script>
        function logout() {
            $.ajax({
                url: '{{ route('ticket.logout') }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Logout successful');
                    window.location.href = '{{ route('login') }}';
                },
                error: function(xhr, status, error) {
                    console.error('Logout failed:', error);
                    alert('Logout failed. Please try again.');
                }
            });
        }

   
    </script>
</body>
</html>