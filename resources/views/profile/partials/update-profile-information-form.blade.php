<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- First Name --}}
        <div>
            <x-input-label for="fname" :value="__('First Name:')" />
            <x-text-input id="fname" name="fname" type="text" class="mt-1 block w-full" :value="old('fname', $user->detail?->fname)" required
                autofocus autocomplete="given-name" />
            <x-input-error class="mt-2" :messages="$errors->get('fname')" />
        </div>

        {{-- Last Name --}}
        <div>
            <x-input-label for="lname" :value="__('Last Name:')" />
            <x-text-input id="lname" name="lname" type="text" class="mt-1 block w-full" :value="old('lname', $user->detail?->lname)"
                required autocomplete="family-name" />
            <x-input-error class="mt-2" :messages="$errors->get('lname')" />
        </div>

        {{-- Phone --}}
        <div>
            <x-input-label for="mobile" :value="__('Phone:')" />
            <x-text-input id="mobile" name="mobile" type="text" class="mt-1 block w-full" :value="old('mobile', $user->detail?->mobile)"
                required autocomplete="tel" />
            <x-input-error class="mt-2" :messages="$errors->get('mobile')" />
        </div>

        {{-- Country --}}
        <div>
            <x-input-label for="country" :value="__('Country:')" />
            <select id="country" name="country"
                class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                <option value="">{{ __('Select Country') }}</option>
                @foreach ($countries as $code => $name)
                    <option value="{{ $code }}"
                        {{ old('country', $user->detail?->country) == $code ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('country')" />
        </div>

        <div class="flex items-center gap-4">
            <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Update Details') }}
            </button>
        </div>
    </form>
</section>
