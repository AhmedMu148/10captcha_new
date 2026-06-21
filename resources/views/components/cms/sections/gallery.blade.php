<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(filled($data['heading'] ?? null))
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-10">
                {{ $data['heading'] }}
            </h2>
        @endif
        
        @if(filled($data['items'] ?? null) && is_array($data['items']))
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach($data['items'] as $item)
                    @if(filled($item['image_url'] ?? null))
                        <div class="group relative overflow-hidden rounded-xl bg-gray-100 shadow-sm hover:shadow-md transition">
                            <img src="{{ $item['image_url'] }}" 
                                 alt="{{ $item['caption'] ?? 'Gallery Image' }}" 
                                 class="w-full h-64 object-cover group-hover:scale-105 transition duration-500"
                                 loading="lazy">
                            @if(filled($item['caption'] ?? null))
                                <div class="absolute bottom-0 inset-x-0 bg-black/60 backdrop-blur-xs text-white p-4 text-sm font-semibold opacity-0 group-hover:opacity-100 transition duration-300">
                                    {{ $item['caption'] }}
                                </div>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</section>
