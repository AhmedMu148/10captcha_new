<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(filled($data['heading'] ?? null))
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">
                {{ $data['heading'] }}
            </h2>
        @endif
        
        @if(filled($data['items'] ?? null) && is_array($data['items']))
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($data['items'] as $item)
                    <div class="text-center bg-gray-50 p-6 rounded-xl border border-gray-100 hover:shadow-sm transition">
                        @if(filled($item['photo_url'] ?? null))
                            <img src="{{ $item['photo_url'] }}" 
                                 alt="{{ $item['name'] ?? 'Team Member' }}" 
                                 class="w-32 height-32 rounded-full mx-auto object-cover mb-4 border-2 border-green-600">
                        @else
                            <div class="w-32 h-32 rounded-full mx-auto bg-gray-200 mb-4 flex items-center justify-center border-2 border-green-600">
                                <i class="las la-user text-5xl text-gray-400"></i>
                            </div>
                        @endif
                        
                        @if(filled($item['name'] ?? null))
                            <h4 class="font-bold text-gray-900 text-lg">
                                {{ $item['name'] }}
                            </h4>
                        @endif
                        
                        @if(filled($item['role'] ?? null))
                            <p class="text-green-600 font-semibold text-xs uppercase tracking-wide mt-1">
                                {{ $item['role'] }}
                            </p>
                        @endif
                        
                        @if(filled($item['bio'] ?? null))
                            <p class="text-gray-500 text-xs mt-3 leading-relaxed">
                                {{ $item['bio'] }}
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
