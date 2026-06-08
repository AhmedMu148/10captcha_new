<x-app-layout>
<section class="py-10">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto mb-12 max-w-3xl text-center">
            <h1 class="mb-4 text-4xl font-extrabold text-gray-900 sm:text-5xl">Custom Image Modules</h1>
            <p class="mb-6 text-lg leading-8 text-gray-600">
                Choose from custom image modules for perfect results in solving image captchas.<br>
                Check the API documentation for <a class="font-bold text-gray-900 transition hover:text-green-600" href="{{ (method_exists(\App\Models\SystemSetting::class, 'apiDocsUrl') ? \App\Models\SystemSetting::apiDocsUrl() : 'https://docs.captchaai.com') }}#solving_normal_captcha" target="_blank"> Solving Normal Captcha <i class="la la-external-link-alt text-sm"></i></a>
            </p>
            <div class="inline-flex flex-wrap items-center justify-center gap-2 rounded-full border border-gray-200 bg-white px-6 py-3 text-sm shadow-sm">
                <span class="text-gray-500">Want to test out our custom modules?</span>
                <a class="font-bold text-gray-900 transition hover:text-green-600" href="{{ route('custom-image.test') }}">
                    Try the Testing Page <i class="la la-arrow-right"></i>
                </a>
            </div>
        </div>

        <livewire:custom-images-table />
    </div>
</section>
</x-app-layout>
