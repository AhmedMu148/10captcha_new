<x-app-layout>
<div class="max-w-3xl mx-auto px-4 py-10" style="background: white; min-height: 100vh;">

    {{-- API Key Box --}}
    <div class="rounded-lg p-6 mb-10 text-center" style="border: 2px dashed #22c55e;">
        <h2 class="font-bold text-lg mb-4">API KEY</h2>

        @if($user->api_key)
            <div class="flex items-center gap-2 mb-4 max-w-md mx-auto">
                <input id="apiKeyInput" type="text" readonly value="{{ $user->api_key }}"
                       class="flex-1 bg-gray-100 border border-gray-300 rounded px-3 py-2 text-sm text-gray-700 truncate" />
                <button onclick="copyApiKey()"
                        class="bg-gray-800 hover:bg-gray-900 text-white text-sm px-4 py-2 rounded transition">
                    Copy
                </button>
            </div>
        @else
            <p class="text-gray-500 text-sm mb-4">No API key yet.</p>
        @endif

        <form method="POST" action="{{ route('api.regenerate') }}">
            @csrf
            <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-6 py-2 rounded transition">
                {{ $user->api_key ? 'Regenerate API key' : 'Generate API key' }}
            </button>
        </form>
    </div>

    {{-- How To Integrate --}}
    <h2 class="text-xl font-bold text-gray-900 mb-1">How To Integrate</h2>
    <p class="text-gray-500 text-sm mb-8">
        We provide an API that allows seamless integration of captcha recognition into any program or script.
        Follow the steps below to integrate 10Captcha with your software.
    </p>

    {{-- Step 1 --}}
    <div class="mb-8">
        <h3 class="text-base font-bold text-gray-800 mb-2">Step 1: Edit the Hosts File</h3>
        <p class="text-gray-500 text-sm mb-3">
            To ensure 10Captcha can properly route requests, you need to modify your
            <strong>host file</strong> by adding OCR Server Endpoint details as following:
        </p>
        <pre class="bg-gray-100 border border-gray-200 rounded p-4 text-xs text-gray-700 leading-relaxed overflow-x-auto"><code># 10Captcha.com begin#
49.13.44.1 rucaptcha.com
49.13.44.1 2captcha.com
# 10Captcha.com end#</code></pre>
    </div>

    {{-- Step 2 --}}
    <div class="mb-8">
        <h3 class="text-base font-bold text-gray-800 mb-2">Step 2: Specify your API Key</h3>
        <p class="text-gray-500 text-sm mb-2">After editing the hosts file, configure your program as follows:</p>
        <ol class="list-decimal list-inside text-sm text-gray-600 space-y-1">
            <li>In your program, select <strong>2Captcha</strong> as the captcha solver if it's supported.</li>
            <li>Enter your <strong>10Captcha API Key</strong> in the relevant field.</li>
        </ol>
    </div>

    {{-- OS Links --}}
    <div class="mb-10">
        <h3 class="text-base font-bold text-gray-800 mb-2">Editing host file based on your operating system</h3>
        <p class="text-gray-500 text-sm mb-2">
            If you need guidance on editing the hosts file, refer to the appropriate link below:
        </p>
        <div class="flex gap-4 text-sm">
            <a href="https://www.howtogeek.com/howto/27350/beginner-geek-how-to-edit-your-hosts-file/"
               target="_blank" class="text-green-600 hover:underline">Windows</a>
            <a href="https://www.howtogeek.com/howto/27350/beginner-geek-how-to-edit-your-hosts-file/"
               target="_blank" class="text-green-600 hover:underline">Linux</a>
        </div>
    </div>

    {{-- Footer Note --}}
    <div class="border-t border-gray-200 pt-6 text-center text-sm text-gray-500 space-y-2">
        <p>If you're looking to integrate our service into your program /script, we've got you covered.</p>
        <p>
            Explore the full capabilities of 10Captcha by referring to our comprehensive
            <a href="{{ url('/api-docs') }}" class="text-green-600 hover:underline">API documentation.</a>
        </p>
    </div>

</div>

@push('scripts')
<script>
function copyApiKey() {
    const input = document.getElementById('apiKeyInput');
    navigator.clipboard.writeText(input.value).then(() => {
        const btn = event.target;
        btn.textContent = 'Copied!';
        setTimeout(() => btn.textContent = 'Copy', 2000);
    });
}
</script>
@endpush
</x-app-layout>
