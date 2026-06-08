@extends('layouts.app')
@section('title', 'Custom Image Modules Testing - ' . config('app.name'))

@php
    $breadcrumbCurrent = "Custom Image Modules Testing";
@endphp

@section('content')
<section class="py-16 min-h-screen">
    <div class="max-w-4xl mx-auto px-4">
        <div>
            <div class="px-6 py-8 sm:px-10">
                <div class="max-w-2xl mx-auto text-center">
                    <h1 class="text-3xl font-bold text-gray-900">Custom Image Modules Captcha Testing</h1>
                    <p class="mt-4 text-gray-600 text-base leading-relaxed">
                        Welcome to the Custom Image Modules Captcha Testing page! Here, you can evaluate the effectiveness of our custom modules for solving CAPTCHA challenges. Upload your CAPTCHA image and compare the output of each module to your known correct result.
                    </p>
                    <p class="mt-2 text-gray-500 text-sm">
                        Learn more about our <a href="https://10captcha.com/custom-images.php" class="text-green-600 font-semibold hover:underline">Custom Image Modules</a> and how they work to solve CAPTCHA challenges.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mt-8 rounded-2xl bg-green-50 p-5 border border-green-200 text-sm text-green-700">
                        <div class="font-semibold mb-3">Validation Errors</div>
                        <ul class="space-y-2">
                            @foreach ($errors->all() as $error)
                                <li class="flex items-start gap-2">
                                    <span class="mt-0.5">•</span>
                                    <span>{{ $error }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mt-8 rounded-2xl bg-orange-50 p-5 border border-orange-200 text-sm text-orange-700">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="mt-10 grid gap-10 lg:grid-cols-[1.3fr_0.7fr]">
                    <div class="space-y-6">
                        <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                            <p class="text-gray-700 text-sm mb-4">Upload your CAPTCHA image to test and compare the results of our custom modules. Find the best match for your needs!</p>
                            <div class="rounded-3xl border border-gray-200 bg-gray-50 p-6">
                                <div class="mb-5 text-sm font-semibold text-gray-900">Please choose Image or enter Base64</div>
                                <div class="flex flex-wrap gap-2 border-b border-gray-200 pb-4 mb-6">
                                    <button type="button" class="tab-btn active rounded-xl px-4 py-3 text-sm font-semibold text-green-600 bg-white shadow-sm transition" data-tab="image-pane">Image</button>
                                    <button type="button" class="tab-btn rounded-xl px-4 py-3 text-sm font-semibold text-gray-500 bg-white shadow-sm transition" data-tab="base64-pane">Base64</button>
                                </div>

                                <form action="{{ route('custom-image.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                                    @csrf

                                    <div class="tab-pane" id="image-pane">
                                        <button type="button" id="uploadCaptchaBtn" class="w-full rounded-2xl border border-gray-300 bg-white px-4 py-3 text-left text-sm text-gray-700 shadow-sm hover:border-green-600 hover:text-green-600 transition">
                                            <span id="uploadCaptchaBtnLabel">Upload CAPTCHA Image</span>
                                            <span id="uploadCaptchaBtnHint" class="ml-2 text-xs text-gray-500">Browse</span>
                                        </button>
                                        <input type="file" id="image" name="image" accept="image/*" class="sr-only" />
                                        <div id="imageUploadStatus" class="mt-3 hidden rounded-2xl border border-green-200 bg-green-50 p-3">
                                            <div class="flex items-center gap-3">
                                                <img id="imagePreview" src="" alt="Selected CAPTCHA preview" class="h-14 w-14 rounded-xl border border-green-200 bg-white object-contain p-1" />
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-sm font-semibold text-green-700">Image selected</p>
                                                    <p id="imageFileName" class="truncate text-xs text-gray-600"></p>
                                                </div>
                                                <button type="button" id="changeImageBtn" class="shrink-0 rounded-xl border border-green-200 bg-white px-3 py-2 text-xs font-semibold text-green-700 hover:border-green-600 transition">Change</button>
                                            </div>
                                        </div>
                                        @error('image')
                                            <p class="mt-2 text-sm text-green-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="tab-pane hidden" id="base64-pane">
                                        <label for="base64" class="block text-sm font-semibold text-gray-900 mb-2">Paste Base64 String</label>
                                        <textarea id="base64" name="base64" rows="5" class="block w-full rounded-2xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 focus:border-green-600 focus:ring-2 focus:ring-green-100" placeholder="Paste your base64 encoded image here..."></textarea>
                                        @error('base64')
                                            <p class="mt-2 text-sm text-green-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="result" class="block text-sm font-semibold text-gray-900 mb-2">Enter Known Correct Result:</label>
                                        <input type="text" id="result" name="result" required autocomplete="off" placeholder="The Known Correct Result" class="block w-full rounded-2xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 focus:border-green-600 focus:ring-2 focus:ring-green-100" />
                                        @error('result')
                                            <p class="mt-2 text-sm text-green-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="flex justify-end">
                                        <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-green-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-green-700">Start Testing</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">How It Works</h2>
                            <ul class="space-y-4 text-sm text-gray-700">
                                <li class="space-y-2">
                                    <p class="font-semibold text-gray-900">Upload CAPTCHA Image:</p>
                                    <p>Start by uploading your CAPTCHA image. This image will be processed by the modules to provide a result.</p>
                                </li>
                                <li class="space-y-2">
                                    <p class="font-semibold text-gray-900">Enter Known Result:</p>
                                    <p>Provide the correct CAPTCHA result (the one you know is right). This helps you compare the accuracy of each module's result.</p>
                                </li>
                                <li class="space-y-2">
                                    <p class="font-semibold text-gray-900">Analyze and Compare:</p>
                                    <p>Submit the form, and our system will process the image with all modules. You will be able to see the results for each module and compare them with your known correct result.</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const tabName = this.getAttribute('data-tab');

            tabBtns.forEach(b => {
                b.classList.remove('active', 'text-green-600', 'border-green-600');
                b.classList.add('text-gray-500');
            });

            tabPanes.forEach(pane => pane.classList.add('hidden'));

            this.classList.add('active', 'text-green-600', 'border-green-600');
            this.classList.remove('text-gray-500');
            document.getElementById(tabName).classList.remove('hidden');
        });
    });

    const uploadCaptchaBtn = document.getElementById('uploadCaptchaBtn');
    const imageInput = document.getElementById('image');
    const uploadCaptchaBtnLabel = document.getElementById('uploadCaptchaBtnLabel');
    const uploadCaptchaBtnHint = document.getElementById('uploadCaptchaBtnHint');
    const imageUploadStatus = document.getElementById('imageUploadStatus');
    const imagePreview = document.getElementById('imagePreview');
    const imageFileName = document.getElementById('imageFileName');
    const changeImageBtn = document.getElementById('changeImageBtn');

    uploadCaptchaBtn?.addEventListener('click', function() {
        imageInput?.click();
    });

    changeImageBtn?.addEventListener('click', function() {
        imageInput?.click();
    });

    imageInput?.addEventListener('change', function() {
        const file = this.files?.[0];

        if (!file) {
            imageUploadStatus?.classList.add('hidden');
            uploadCaptchaBtn?.classList.remove('border-green-600', 'bg-green-50', 'text-green-700');
            uploadCaptchaBtnLabel.textContent = 'Upload CAPTCHA Image';
            uploadCaptchaBtnHint.textContent = 'Browse';
            imagePreview.src = '';
            imageFileName.textContent = '';
            return;
        }

        uploadCaptchaBtn?.classList.add('border-green-600', 'bg-green-50', 'text-green-700');
        uploadCaptchaBtnLabel.textContent = 'Image selected';
        uploadCaptchaBtnHint.textContent = 'Change';
        imageFileName.textContent = file.name;
        imageUploadStatus?.classList.remove('hidden');

        const reader = new FileReader();
        reader.addEventListener('load', function(event) {
            imagePreview.src = event.target.result;
        });
        reader.readAsDataURL(file);
    });
});
</script>
@endsection

