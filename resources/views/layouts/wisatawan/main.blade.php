@php $user = $user ?? auth()->user(); @endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('page_title', 'Portal Wisatawan – WanderMed')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="{{ asset('css/dashboard-wisatawan.css') }}" rel="stylesheet">
    {{-- Inline script tema: set SEBELUM render agar tidak flash --}}
    <script src="{{ asset('js/theme-init-wisatawan-head.js') }}"></script>
    @stack('styles')
</head>
<body id="appBody">
<script src="{{ asset('js/theme-init-wisatawan-body.js') }}"></script>

@include('layouts.wisatawan.navbar')

<div class="w-page-wrapper">
    @include('layouts.wisatawan.profile_strip')

    <div class="w-layout">
        @include('layouts.wisatawan.sidebar')

        <main class="w-main" id="wMain">
            @yield('content')
        </main>
    </div>

    @yield('additional_styles')

    <script src="{{ asset('js/dashboard-wisatawan.js') }}"></script>
    @stack('scripts')
</div>
</body>
</html>
