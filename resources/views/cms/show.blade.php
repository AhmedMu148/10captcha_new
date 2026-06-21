<x-app-layout>
    @push('styles')
        @if(filled($page->seo_title ?? null))
            <title>{{ $page->seo_title }}</title>
        @endif
        @if(filled($page->seo_description ?? null))
            <meta name="description" content="{{ $page->seo_description }}">
        @endif
        @if(filled($page->seo_keywords ?? null))
            <meta name="keywords" content="{{ $page->seo_keywords }}">
        @endif
        @if(filled($page->canonical_url ?? null))
            <link rel="canonical" href="{{ $page->canonical_url }}">
        @endif
    @endpush

    <div class="cms-page-container">
        @foreach($renderedSections as $rendered)
            <div class="{{ $rendered->wrapperClass ?? '' }}"{!! filled($rendered->anchorId ?? null) ? ' id="' . $rendered->anchorId . '"' : '' !!}>
                {!! $rendered->html !!}
            </div>
        @endforeach
    </div>
</x-app-layout>
