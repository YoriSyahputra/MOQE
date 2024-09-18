<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') MonitoringQE</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon"/>



</head>
<body class="bg-gray-100 h-screen flex flex-col">
    <header class="bg-red-700 text-white p-4">
        <h1 class="text-2xl font-bold">MonitoringQE</h1>
    </header>

    <main class="flex-grow flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
            @yield('content')
        </div>
    </main>

    <footer class="bg-gray-200 text-center p-4">
        <p>&copy; 2024 Ticket System. Telkom Indonesia.</p>
    </footer>
</body>
</html>