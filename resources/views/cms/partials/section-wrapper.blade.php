<section class="{{ $wrapperClass ?? '' }}"{!! filled($anchorId ?? null) ? ' id="' . $anchorId . '"' : '' !!}>
    {!! $content !!}
</section>
