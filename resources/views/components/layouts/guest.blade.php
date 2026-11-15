<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Briefly' }} : Masuk</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 dark:bg-[#0A0F1D] text-slate-900 dark:text-slate-100 min-h-screen flex flex-col relative selection:bg-amber-400 selection:text-slate-900 antialiased">

    <!-- Main Page Content (Centered) -->
    <main class="relative z-10 flex-1 flex items-center justify-center px-4 py-12 w-full">
        {{ $slot }}
    </main>

    <!-- Global Toast Container -->
    <div id="toast-container" aria-live="polite"></div>

    <!-- Footer -->
    <footer class="relative z-10 border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0B0F19] py-4 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4">
            Briefly : Asisten Produksi Media dan Desain Kampus • Himpunan Mahasiswa
        </div>
    </footer>
</body>
</html>
