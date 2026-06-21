<section class="bg-white py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(filled($data['heading'] ?? null))
            <h2 class="text-3xl font-bold text-gray-900 mb-6 text-center">
                {{ $data['heading'] }}
            </h2>
        @endif
        
        @if(filled($data['body'] ?? null))
            <div class="prose prose-slate max-w-none text-gray-600 leading-relaxed space-y-4">
                {!! \Illuminate\Support\Str::markdown($data['body']) !!}
            </div>
        @endif
    </div>
</section>
