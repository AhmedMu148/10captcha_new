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

        <style>[x-cloak]{display:none!important}</style>
    </head>
    <body class="font-sans antialiased">

        <div class="min-h-screen flex">

            {{-- ===== Left Green Panel ===== --}}
            <div class="hidden lg:flex lg:w-1/2 bg-green-600 flex-col items-center justify-center p-10 text-white relative overflow-hidden">
                {{ $panel ?? '' }}
            </div>

            {{-- ===== Right White Form Panel ===== --}}
            <div class="w-full lg:w-1/2 flex items-center justify-center px-8 py-10 bg-white">
                <div class="w-full max-w-md">

                    {{-- Logo --}}
                    <div class="text-center mb-8">
                        <a href="/" class="text-3xl font-extrabold text-green-600">
                            10Captcha
                        </a>
                    </div>

                    {{ $slot }}

                </div>
            </div>

        </div>

        {{-- Flash Notifications --}}
        @foreach (['success' => 'green', 'error' => 'red', 'warning' => 'yellow', 'info' => 'blue'] as $type => $color)
            @session($type)
            <div
                x-data="{ show: false }"
                x-init="$nextTick(() => { show = true; setTimeout(() => show = false, 5000) })"
                x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-x-10"
                x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-x-0"
                x-transition:leave-end="opacity-0 translate-x-10"
                class="fixed top-5 right-5 z-[9999] flex items-start gap-3 border-l-4 rounded-lg shadow-lg px-4 py-3 bg-{{ $color }}-50 border-{{ $color }}-400 text-{{ $color }}-800"
                style="min-width:280px;max-width:360px;display:none"
            >
                <span class="mt-0.5 text-lg shrink-0">
                    @if($type === 'success') <i class="las la-check-circle"></i>
                    @elseif($type === 'error') <i class="las la-times-circle"></i>
                    @elseif($type === 'warning') <i class="las la-exclamation-triangle"></i>
                    @else <i class="las la-info-circle"></i>
                    @endif
                </span>
                <span class="flex-1 text-sm font-medium leading-snug">{{ $value }}</span>
                <button @click="show = false" class="ml-1 shrink-0 opacity-50 hover:opacity-100 transition">
                    <i class="las la-times text-base"></i>
                </button>
            </div>
            @endsession
        @endforeach

    </body>
</html>
