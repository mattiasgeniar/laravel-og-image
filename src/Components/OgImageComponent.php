<?php

namespace Spatie\OgImage\Components;

use Closure;
use Illuminate\View\Component;
use Spatie\OgImage\OgImage;

class OgImageComponent extends Component
{
    public function __construct(
        public ?string $view = null,
        public array $data = [],
        public ?string $format = null,
    ) {}

    public function render(): Closure
    {
        return function (array $data) {
            if ($this->view) {
                $html = view($this->view, $this->data)->render();
            } else {
                $html = trim($data['slot']->toHtml());
            }

            return app(OgImage::class)->html($html, $this->format)->toHtml();
        };
    }
}
