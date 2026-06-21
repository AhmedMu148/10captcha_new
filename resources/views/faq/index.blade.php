<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 py-12">

        @php $allFaqs = $categories->flatMap->faqs; @endphp

        @forelse ($allFaqs as $faq)
            <div x-data="{ open: false }"
                class="mb-6 bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-lg">
                {{-- Header --}}
                <button @click="open = !open"
                    class="w-full py-5 px-6 text-left font-semibold transition duration-300 focus:outline-none flex justify-between items-center"
                    :class="open ? 'text-green-600' : 'text-slate-800 hover:text-green-600'">
                    <span>{{ $faq->question }}</span>
                    <i class="las la-angle-down text-xl transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                </button>

                {{-- Answer --}}
                <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                    class="bg-white px-6 pb-6 pt-2 text-gray-600 text-sm leading-relaxed border-t border-gray-50">
                    {!! nl2br(e($faq->answer)) !!}
                </div>
            </div>
        @empty
            <div class="text-gray-400 py-20">
                <i class="las la-question-circle text-5xl mb-3 block"></i>
                No FAQs available yet.
            </div>
        @endforelse

    </div>
</x-app-layout>
