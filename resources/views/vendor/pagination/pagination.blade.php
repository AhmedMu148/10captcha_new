@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex flex-wrap items-center gap-1">
        @if ($paginator->onFirstPage())
            <span class="min-w-8 cursor-not-allowed rounded border border-gray-300 bg-white px-3 py-2 text-xs text-gray-400">Previous</span>
        @else
            <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" rel="prev" class="min-w-8 rounded border border-gray-300 bg-white px-3 py-2 text-xs text-gray-700 transition hover:bg-gray-100">Previous</button>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-2 text-gray-400">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="min-w-8 rounded border border-green-600 bg-green-600 px-3 py-2 text-center text-xs text-white">{{ $page }}</span>
                    @else
                        <button type="button" wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" class="min-w-8 rounded border border-gray-300 bg-white px-3 py-2 text-xs text-gray-700 transition hover:bg-gray-100">{{ $page }}</button>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" rel="next" class="min-w-8 rounded border border-gray-300 bg-white px-3 py-2 text-xs text-gray-700 transition hover:bg-gray-100">Next</button>
        @else
            <span class="min-w-8 cursor-not-allowed rounded border border-gray-300 bg-white px-3 py-2 text-xs text-gray-400">Next</span>
        @endif
    </nav>
@endif
