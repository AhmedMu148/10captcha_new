<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 py-12">

        @php $allFaqs = $categories->flatMap->faqs; @endphp

        @forelse ($allFaqs as $faq)
            <div
                x-data="{ open: {{ $loop->first ? 'true' : 'false' }} }"
                class="mb-4 rounded-lg overflow-hidden shadow-sm border border-gray-200"
            >
                {{-- Header --}}
                <button
                    @click="open = !open"
                    class="w-full flex items-center justify-between px-6 py-4 text-left font-semibold transition"
                    :class="open ? 'bg-green-600 text-white' : 'bg-white text-gray-800 hover:bg-gray-50'"
                >
                    <span>{{ $faq->question }}</span>
                </button>

                {{-- Answer --}}
                <div
                    x-show="open"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="bg-white px-6 py-5 text-gray-600 text-sm leading-relaxed border-t border-gray-100"
                >
                    {!! nl2br(e($faq->answer)) !!}
                </div>
            </div>
        @empty
            <div class="text-center text-gray-400 py-20">
                <i class="las la-question-circle text-5xl mb-3 block"></i>
                No FAQs available yet.
            </div>
        @endforelse

    </div>
</x-app-layout>
