<section class="bg-green-600 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
        @if(filled($data['heading'] ?? null))
            <h2 class="text-3xl font-extrabold mb-4">
                {{ $data['heading'] }}
            </h2>
        @endif
        
        @if(filled($data['body'] ?? null))
            <p class="text-white text-lg opacity-90 mb-8 max-w-2xl mx-auto">
                {{ $data['body'] }}
            </p>
        @endif
        
        @if(filled($data['button_label'] ?? null) && filled($data['button_url'] ?? null))
            <div class="max-w-xs mx-auto">
                <a href="{{ $data['button_url'] }}" 
                   class="block w-full py-4 bg-gray-900 text-white font-bold text-lg rounded-lg hover:bg-gray-800 transition shadow-md hover:shadow-lg">
                    {{ $data['button_label'] }}
                </a>
            </div>
        @endif
    </div>
</section>
