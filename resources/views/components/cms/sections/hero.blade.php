<section class="bg-white py-12 lg:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto">
            @if(filled($data['eyebrow'] ?? null))
                <span class="text-green-600 font-extrabold text-sm tracking-widest uppercase block mb-3">
                    {{ $data['eyebrow'] }}
                </span>
            @endif
            
            @if(filled($data['heading'] ?? null))
                <h1 class="text-4xl lg:text-6xl font-extrabold text-gray-900 mb-6 leading-tight">
                    {!! e($data['heading']) !!}
                </h1>
            @endif
            
            @if(filled($data['subheading'] ?? null))
                <p class="text-gray-500 text-lg lg:text-xl leading-relaxed mb-8">
                    {{ $data['subheading'] }}
                </p>
            @endif
            
            @if(filled($data['button_label'] ?? null) && filled($data['button_url'] ?? null))
                <div>
                    <a href="{{ $data['button_url'] }}" 
                       class="inline-block px-8 py-4 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition shadow-md hover:shadow-lg">
                        {{ $data['button_label'] }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>
