<footer class="bg-gray-900 text-white py-10 px-4 text-center" id="footer-10cap">

    {{-- Nav Links --}}
    <div class="flex flex-wrap justify-center gap-x-2 gap-y-1 mb-6 text-sm">
        @foreach([
            ['url' => '/', 'label' => 'Home'],
            ['url' => '/topup', 'label' => 'Add Funds'],
            ['url' => '/api-docs', 'label' => 'API Documentation'],
            ['url' => '/custom-images', 'label' => 'Custom Image Modules'],
            ['url' => '/tos', 'label' => 'Terms'],
            ['url' => '/privacy-policy', 'label' => 'Privacy'],
            ['url' => '/partnership', 'label' => 'Partnership'],
            ['url' => '/tickets/new', 'label' => 'Contact Us'],
            ['url' => '/faq', 'label' => 'FAQ'],
        ] as $i => $link)
            @if($i > 0)<span class="text-gray-600">|</span>@endif
            <a href="{{ url($link['url']) }}" class="text-gray-300 hover:text-white transition">{{ $link['label'] }}</a>
        @endforeach
        <span class="text-gray-600">|</span>
        <a href="https://help.10captcha.com/" class="text-gray-300 hover:text-white transition" target="_blank" rel="noopener">Help Center</a>
    </div>

    {{-- Payment Icons --}}
    <div class="flex justify-center gap-2 mb-6 text-2xl">
        <i class="lab la-paypal bg-gray-700 rounded px-2 py-0.5"></i>
        <i class="lab la-cc-visa bg-gray-700 rounded px-2 py-0.5"></i>
        <i class="lab la-cc-apple-pay bg-gray-700 rounded px-2 py-0.5"></i>
        <i class="lab la-cc-amex bg-gray-700 rounded px-2 py-0.5"></i>
        <i class="lab la-cc-discover bg-gray-700 rounded px-2 py-0.5"></i>
    </div>

    {{-- Copyright --}}
    <div class="text-sm text-gray-400 mb-4">
        &copy; {{ date('Y') }} Copyright.
        <a class="text-gray-300 hover:text-white transition" href="https://10captcha.com/">10captcha.com</a>
    </div>

    {{-- Social Links --}}
    <div class="flex justify-center gap-4 mb-4 text-2xl">
        <a href="https://www.facebook.com/10Captcha" class="text-gray-400 hover:text-white transition" target="_blank" rel="noopener">
            <i class="lab la-facebook"></i>
        </a>
        <a href="https://www.instagram.com/10Captcha" class="text-gray-400 hover:text-white transition" target="_blank" rel="noopener">
            <i class="lab la-instagram"></i>
        </a>
    </div>

    {{-- Address --}}
    <div class="text-xs text-gray-500">
        <i class="las la-map-marker mr-1"></i>
        Office number 5, 45th Floor, The One Tower, Sheikh Zayed Road, Dubai, UAE
    </div>

</footer>
