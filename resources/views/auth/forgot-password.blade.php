<x-guest-layout>

    {{-- Left Panel --}}
    <x-slot name="panel">
        <img src="{{ asset('assets/img/login.png') }}" class="w-full max-w-sm" alt="Login illustration">
        <h3 class="text-2xl font-bold mt-6 text-center">Forgot Password?</h3>
    </x-slot>

    {{-- Page Heading --}}
    <h2 class="text-2xl font-bold text-gray-900 text-center mb-1">Reset Password</h2>
    <p class="text-gray-500 text-center text-sm mb-6">Enter your email address to receive a reset link</p>

    <div class="mb-4 text-sm text-gray-600">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-6">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Submit --}}
        <button type="submit"
            class="w-full py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition text-base">
            {{ __('Email Password Reset Link') }}
        </button>

        <p class="text-center text-sm text-gray-500 mt-4">
            Remembered your password?
            <a href="{{ route('login') }}" class="text-green-600 font-medium hover:underline">Sign in</a>
        </p>
    </form>
</x-guest-layout>
