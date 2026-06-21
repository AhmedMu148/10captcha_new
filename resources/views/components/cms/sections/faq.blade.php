<section class="py-12 bg-gray-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(filled($data['heading'] ?? null))
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-4">
                {{ $data['heading'] }}
            </h2>
        @endif
        
        @if(filled($data['description'] ?? null))
            <p class="text-gray-500 text-center mb-10 max-w-2xl mx-auto">
                {{ $data['description'] }}
            </p>
        @endif

        @if(filled($data['items'] ?? null) && is_array($data['items']))
            <div class="mb-8">
                @foreach($data['items'] as $index => $item)
                    <div x-data="{ open: false }" 
                         class="mb-6 bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 transition-all duration-300 hover:shadow-md">
                        <button @click="open = !open" 
                                class="w-full py-4 px-6 text-left font-semibold transition duration-300 focus:outline-none flex justify-between items-center"
                                :class="open ? 'text-green-600' : 'text-slate-800 hover:text-green-600'">
                            <span>{{ $item['question'] ?? '' }}</span>
                            <i class="las la-angle-down text-xl transition-transform duration-300"
                               :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="bg-white px-6 pb-6 pt-2 text-gray-600 text-sm leading-relaxed border-t border-gray-50">
                            {!! nl2br(e($item['answer'] ?? '')) !!}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
