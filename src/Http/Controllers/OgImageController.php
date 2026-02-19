<?php

namespace Spatie\OgImage\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Spatie\OgImage\OgImage;
use Spatie\OgImage\OgImageGenerator;

class OgImageController
{
    public function __invoke(Request $request, string $filename): mixed
    {
        $parts = explode('.', $filename, 2);

        if (count($parts) !== 2) {
            abort(404);
        }

        [$hash, $format] = $parts;

        $ogImage = app(OgImage::class);

        $imageUrl = $ogImage->getImageUrlFromCache($hash, $format);

        if ($imageUrl) {
            return redirect($imageUrl);
        }

        $pageUrl = $ogImage->getUrlFromCache($hash);

        if ($pageUrl === null) {
            abort(404);
        }

        $path = $ogImage->imagePath($hash, $format);

        Cache::lock("og-image-generate:{$hash}", 60)->block(60, function () use ($ogImage, $hash, $format, $pageUrl, $path) {
            if ($ogImage->getImageUrlFromCache($hash, $format)) {
                return;
            }

            app(OgImageGenerator::class)->generate($pageUrl.'?ogimage', $path, $format);

            $disk = Storage::disk(config('og-image.disk', 'public'));
            $ogImage->storeImageUrlInCache($hash, $format, $disk->url($path));
        });

        return redirect($ogImage->getImageUrlFromCache($hash, $format));
    }
}
