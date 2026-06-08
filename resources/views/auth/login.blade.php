<x-guest-layout>

    {{-- Left Panel --}}
    <x-slot name="panel">
        <img src="{{ asset('assets/img/login.png') }}" class="w-full max-w-sm" alt="Login illustration">
        <h3 class="text-2xl font-bold mt-6 text-center">Welcome Back!</h3>
    </x-slot>

    {{-- Page Heading --}}
    <h2 class="text-2xl font-bold text-gray-900 text-center mb-1">Sign In</h2>
    <p class="text-gray-500 text-center text-sm mb-6">sign in to your account to continue</p>

    {{-- Session Status --}}
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email --}}
        <div class="mb-4">
            <x-input-label for="email" :value="__('Email address')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Password --}}
        <div class="mb-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Remember Me + Forgot Password --}}
        <div class="flex items-center justify-between mb-6">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                       class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500"
                       name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   class="text-sm text-green-600 hover:underline">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        {{-- Submit --}}
        <button type="submit"
                class="w-full py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition text-base">
            Sign in
        </button>

        <p class="text-center text-sm text-gray-500 mt-4">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-green-600 font-medium hover:underline">Sign up</a>
        </p>
    </form>

</x-guest-layout>

