<x-guest-layout>
    {{-- Left Panel --}}
    <x-slot name="panel">
        <img src="{{ asset('assets/img/login.png') }}" class="w-full max-w-sm" alt="Login illustration">
        <h3 class="text-2xl font-bold mt-6 text-center">Reset Your Password</h3>
    </x-slot>

    {{-- Page Heading --}}
    <h2 class="text-2xl font-bold text-gray-900 text-center mb-1">New Password</h2>
    <p class="text-gray-500 text-center text-sm mb-6">Create a secure new password for your account</p>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address (Hidden) -->
        <input type="hidden" name="email" value="{{ old('email', $request->email) }}">
        @error('email')
            <div class="mb-4 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg px-4 py-3">
                {{ $message }}
            </div>
        @enderror

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('New Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="new-password" autofocus />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- Submit --}}
        <button type="submit"
            class="w-full py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition text-base mt-6">
            {{ __('Reset Password') }}
        </button>
    </form>
</x-guest-layout>
