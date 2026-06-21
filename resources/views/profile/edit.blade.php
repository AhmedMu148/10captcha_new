<x-app-layout>

    <div class="max-w-7xl mx-auto px-4 py-12">
        <h2 class="text-3xl text-center font-semibold mb-12">
            Profile Update
        </h2>
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="p-4 lg:col-span-6 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 lg:col-span-6 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
