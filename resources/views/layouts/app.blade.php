<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Line Awesome Icons -->
        <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">

        @livewireStyles
        @livewireScriptConfig

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')

        <style>[x-cloak]{display:none!important}</style>
    </head>
    <body class="font-sans antialiased">
        @include('layouts.partials.navbar')
        <div class="min-h-screen">

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @hasSection('content')
                    @yield('content')
                @elseif(isset($slot))
                    {{ $slot }}
                @endif
            </main>
        </div>
        @include('layouts.partials.footer')

        <x-notify-message />

        {{-- Global Loading Overlay --}}
        <div id="global-loader-overlay" style="display:none; position:fixed; inset:0; background:rgba(255,255,255,0.7); backdrop-filter:blur(4px); -webkit-backdrop-filter:blur(4px); z-index:99999; flex-direction:column; align-items:center; justify-content:center; gap:16px;">
            <div style="width:48px; height:48px; border:4px solid #e2e8f0; border-top-color:#16a34a; border-radius:50%; animation:spin 1s linear infinite;"></div>
            <span style="font-family:'figtree',sans-serif; font-size:16px; font-weight:600; color:#1f2937;">Processing, please wait...</span>
        </div>

        <style>
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        </style>

        <script>
        document.addEventListener('DOMContentLoaded', () => {
            const loader = document.getElementById('global-loader-overlay');

            // Show loader for links with the 'show-loader' class
            document.querySelectorAll('.show-loader').forEach(el => {
                el.addEventListener('click', () => {
                    if (loader) loader.style.display = 'flex';
                });
            });

            // Show loader for standard form submissions
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', () => {
                    if (loader) loader.style.display = 'flex';
                });
            });
        });

        // Show/hide loader during Livewire requests (e.g. modals opening, loading tables, etc.)
        document.addEventListener('livewire:init', () => {
            const loader = document.getElementById('global-loader-overlay');
            
            Livewire.hook('request', ({ respond, succeed, fail }) => {
                if (loader) loader.style.display = 'flex';

                respond(() => {
                    if (loader) loader.style.display = 'none';
                });
                
                succeed(() => {
                    if (loader) loader.style.display = 'none';
                });

                fail(() => {
                    if (loader) loader.style.display = 'none';
                });
            });
        });
        </script>

        @stack('scripts')
    </body>
</html>
