<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(filled($data['heading'] ?? null))
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">
                {{ $data['heading'] }}
            </h2>
        @endif
        
        @if(filled($data['items'] ?? null) && is_array($data['items']))
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                @foreach($data['items'] as $item)
                    <div class="bg-white shadow rounded-xl p-8 border border-gray-200 flex flex-col justify-between hover:shadow-md transition">
                        <div>
                            @if(filled($item['name'] ?? null))
                                <h3 class="text-xl font-bold text-gray-900 mb-2">
                                    {{ $item['name'] }}
                                </h3>
                            @endif
                            
                            <div class="flex items-baseline mb-6">
                                <span class="text-4xl font-extrabold text-green-600">
                                    {{ $item['price'] ?? '$0' }}
                                </span>
                                @if(filled($item['period'] ?? null))
                                    <span class="text-gray-500 text-sm ml-2">
                                        / {{ $item['period'] }}
                                    </span>
                                @endif
                            </div>
                            
                            @if(filled($item['features'] ?? null) && is_array($item['features']))
                                <ul class="space-y-3 mb-8 text-sm text-gray-600">
                                    @foreach($item['features'] as $feature)
                                        <li class="flex items-center gap-2">
                                            <i class="las la-check text-green-600 text-lg"></i>
                                            <span>{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                        
                        @if(filled($item['button_label'] ?? null) && filled($item['button_url'] ?? null))
                            <a href="{{ $item['button_url'] }}" 
                               class="block w-full py-3 text-center bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition">
                                {{ $item['button_label'] }}
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
