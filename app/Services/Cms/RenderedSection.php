<?php

namespace App\Services\Cms;

class RenderedSection
{
    public int $id;
    public ?string $title;
    public ?string $wrapperClass;
    public ?string $anchorId;
    public string $html;

    public function __construct(int $id, ?string $title, ?string $wrapperClass, ?string $anchorId, string $html)
    {
        $this->id = $id;
        $this->title = $title;
        $this->wrapperClass = $wrapperClass;
        $this->anchorId = $anchorId;
        $this->html = $html;
    }
}
