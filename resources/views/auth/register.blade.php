<x-guest-layout>

    {{-- Left Panel --}}
    <x-slot name="panel">
        <img src="{{ asset('assets/img/login.png') }}" class="w-full max-w-sm" alt="Register illustration">
        <h3 class="text-2xl font-bold mt-6 text-center">User Access Requested!</h3>
    </x-slot>

    {{-- Page Heading --}}
    <h2 class="text-2xl font-bold text-gray-900 text-center mb-1">User Register</h2>
    <p class="text-gray-500 text-center text-sm mb-6">create your account it takes only a few moments</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Name --}}
        <div class="mb-4">
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        {{-- Email --}}
        <div class="mb-4">
            <x-input-label for="email" :value="__('Email address')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Password --}}
        <div class="mb-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Confirm Password --}}
        <div class="mb-6">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- Submit --}}
        <button type="submit"
                class="w-full py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition text-base">
            Sign up
        </button>

        <p class="text-center text-sm text-gray-500 mt-4">
            Already have account?
            <a href="{{ route('login') }}" class="text-green-600 font-medium hover:underline">Sign in</a>
        </p>
    </form>

</x-guest-layout>

