<?php

namespace Spatie\OgImage\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\OgImage\Actions\GenerateOgImageAction;
use Spatie\OgImage\OgImageGenerator;
use Symfony\Component\HttpFoundation\Response;

class OgImageController
{
    public function __invoke(Request $request, string $filename): Response
    {
        $action = OgImageGenerator::getActionClass('generate_og_image', GenerateOgImageAction::class);

        return $action->execute($filename);
    }
}
