<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(filled($data['heading'] ?? null))
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-4">
                {{ $data['heading'] }}
            </h2>
        @endif
        
        @if(filled($data['subheading'] ?? null))
            <p class="text-gray-500 text-center mb-12 max-w-2xl mx-auto">
                {{ $data['subheading'] }}
            </p>
        @endif
        
        @if(filled($data['items'] ?? null) && is_array($data['items']))
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($data['items'] as $item)
                    <div class="bg-gray-50 shadow-sm rounded-xl p-6 border border-gray-100 flex flex-col justify-between">
                        <div>
                            @if(filled($item['rating'] ?? null))
                                <div class="flex items-center gap-1 mb-4">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= ($item['rating'] ?? 5))
                                            <i class="las la-star text-yellow-500 text-lg"></i>
                                        @else
                                            <i class="lar la-star text-gray-300 text-lg"></i>
                                        @endif
                                    @endfor
                                </div>
                            @endif
                            
                            @if(filled($item['quote'] ?? null))
                                <blockquote class="text-gray-600 italic text-sm leading-relaxed mb-6">
                                    "{{ $item['quote'] }}"
                                </blockquote>
                            @endif
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <div>
                                @if(filled($item['author'] ?? null))
                                    <h4 class="font-bold text-gray-900 text-sm">
                                        {{ $item['author'] }}
                                    </h4>
                                @endif
                                @if(filled($item['role'] ?? null) || filled($item['company'] ?? null))
                                    <p class="text-gray-500 text-xs mt-0.5">
                                        {{ $item['role'] ?? '' }}
                                        @if(filled($item['role'] ?? null) && filled($item['company'] ?? null))
                                            at
                                        @endif
                                        {{ $item['company'] ?? '' }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
