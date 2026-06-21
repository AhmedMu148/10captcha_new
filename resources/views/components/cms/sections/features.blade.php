<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(filled($data['heading'] ?? null))
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-4">
                {{ $data['heading'] }}
            </h2>
        @endif
        
        @if(filled($data['description'] ?? null))
            <p class="text-gray-500 text-center mb-12 max-w-2xl mx-auto">
                {{ $data['description'] }}
            </p>
        @endif
        
        @if(filled($data['items'] ?? null) && is_array($data['items']))
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($data['items'] as $item)
                    <div class="bg-white shadow rounded-xl p-6 hover:shadow-md transition duration-300">
                        @if(filled($item['icon'] ?? null))
                            <i class="{{ $item['icon'] }} text-6xl text-green-600 block mb-4"></i>
                        @endif
                        @if(filled($item['title'] ?? null))
                            <h5 class="font-bold text-gray-900 mt-2 mb-2 text-lg">
                                {{ $item['title'] }}
                            </h5>
                        @endif
                        @if(filled($item['description'] ?? null))
                            <p class="text-gray-500 text-sm leading-relaxed">
                                {{ $item['description'] }}
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
