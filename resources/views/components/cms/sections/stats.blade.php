<section class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(filled($data['heading'] ?? null))
            <h2 class="text-3xl font-bold text-center text-white mb-10">
                {{ $data['heading'] }}
            </h2>
        @endif
        
        @if(filled($data['items'] ?? null) && is_array($data['items']))
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                @foreach($data['items'] as $item)
                    <div class="py-4">
                        <div class="text-4xl lg:text-3xl font-extrabold text-green-500">
                            {{ $item['value'] ?? '' }}
                        </div>
                        @if(filled($item['label'] ?? null))
                            <div class="mt-3 text-gray-400 text-base">
                                {{ $item['label'] }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
