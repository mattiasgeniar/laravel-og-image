<?php

use Spatie\OgImage\Actions\GenerateOgImageAction;
use Spatie\OgImage\Actions\InjectOgImageFallbackAction;
use Spatie\OgImage\Actions\RenderOgImageScreenshotAction;

return [

    /*
     * The filesystem disk used to store generated OG images.
     */
    'disk' => 'public',

    /*
     * The path within the disk where OG images will be stored.
     */
    'path' => 'og-images',

    /*
     * The dimensions of the generated OG images in pixels.
     */
    'width' => 1200,
    'height' => 630,

    /*
     * The default image format. Supported: "jpeg", "png", "webp".
     */
    'format' => 'jpeg',

    /*
     * The actions used by this package. You can replace any of them with
     * your own class to customize the behavior. Your custom class should
     * extend the default action.
     *
     * Learn more: https://spatie.be/docs/laravel-og-image/v1/advanced-usage/customizing-actions
     */
    'actions' => [
        'generate_og_image' => GenerateOgImageAction::class,
        'inject_og_image_fallback' => InjectOgImageFallbackAction::class,
        'render_og_image_screenshot' => RenderOgImageScreenshotAction::class,
    ],

];
