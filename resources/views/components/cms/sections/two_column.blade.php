<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(filled($data['heading'] ?? null))
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">
                {{ $data['heading'] }}
            </h2>
        @endif
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-start">
            <div class="prose prose-slate max-w-none text-gray-600 leading-relaxed">
                @if(filled($data['left'] ?? null))
                    {!! \Illuminate\Support\Str::markdown($data['left']) !!}
                @endif
            </div>
            <div class="prose prose-slate max-w-none text-gray-600 leading-relaxed">
                @if(filled($data['right'] ?? null))
                    {!! \Illuminate\Support\Str::markdown($data['right']) !!}
                @endif
            </div>
        </div>
    </div>
</section>
