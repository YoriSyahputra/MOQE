<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Monitoring QE')</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{asset ('CSS/dashall.css')}}">
</head>

<body>
    <div class="main-header">
        <div class="container">
            <div class="header">
                <h1>Monitoring QE</h1>
                <div class="profile">
                    @if (auth()->user()->profile_photo)
                        <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="profile_photo" class="minta" onclick="openModal('{{ asset('storage/' . auth()->user()->profile_photo) }}', 'image')">
                    @else
                        <img src="{{ asset('storage/default-profile.jpg') }}" alt="Default Profile" class="">
                    @endif
                    <span class="user-name" class="aman">{{ auth()->user()->name }}</span>
                    <form action="{{ route('ticket.logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="edas">Logout</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="main-content">
            <div class="sidebar">
                <h2>Menu</h2>
                <ul>  
                    <li><a href="{{ route('ticket.create') }}">Create Ticket</a></li>
                    <li><a href="{{ route('ticket.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('user.settings') }}">Settings</a></li>
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
        $(window).scroll(function() {
          if ($(window).scrollTop() > 0) {
            $('.header').addClass('sticky');
          } else {
            $('.header').removeClass('sticky');
          }
        });
    </script>
</body>
</html>