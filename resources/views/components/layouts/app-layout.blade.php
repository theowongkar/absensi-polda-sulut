<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {{-- Metadata --}}
    <meta name="description"
        content="Website Kepolisian Daerah (POLDA), Sulawesi Utara. Dirancang khusus untuk sistem informasi karyawan dan absensi.">
    <meta name="keywords" content="Absensi Polda Sulut, sistem informasi management karyawan, layanan absensi online">
    <meta name="author" content="Kepolisian Daerah Sulawesi Utara">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="og:title" content="Polda - {{ $title }}">
    <meta property="og:description" content="Website Kepolisian Daerah (POLDA), Sulawesi Utara.">
    <meta property="og:image" content="{{ asset('img/hero-image.webp') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="robots" content="noindex, nofollow">

    {{-- Favicon --}}
    <link rel="shortcut icon" href="{{ asset('img/application-logo.svg') }}" type="image/x-icon">

    {{-- Judul Halaman --}}
    <title>Polda - {{ $title }}</title>

    {{-- Framework Frontend --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Script Tambahan --}}
    @stack('scripts')

    {{-- Default CSS --}}
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="antialiased">

    <div x-data="{ sidebarOpen: false }" class="relative h-screen flex overflow-hidden" x-cloak>

        {{-- Navigasi --}}
        @include('components.layouts.partials.app-navigation')

        {{-- Layout Utama --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            {{-- Header --}}
            @include('components.layouts.partials.app-header')

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto p-5 bg-gray-100">
                {{ $slot }}
            </main>
        </div>
    </div>

</body>

</html>
