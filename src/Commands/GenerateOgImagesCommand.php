<?php

namespace Spatie\OgImage\Commands;

use Illuminate\Console\Command;
use Spatie\OgImage\OgImageGenerator;
use Throwable;

class GenerateOgImagesCommand extends Command
{
    public $signature = 'og-image:generate {urls*}';

    public $description = 'Pre-generate OG images for one or more URLs';

    public function handle(): int
    {
        $generator = app(OgImageGenerator::class);

        $results = collect($this->argument('urls'))->map(function (string $url) use ($generator) {
            try {
                $imageUrl = $generator->generateForUrl($url);
                $this->components->info("Generated: {$url} -> {$imageUrl}");

                return true;
            } catch (Throwable $exception) {
                $this->components->error("Failed: {$url} - {$exception->getMessage()}");

                return false;
            }
        });

        $succeeded = $results->filter()->count();

        $this->components->info("Done. {$succeeded}/{$results->count()} OG image(s) generated.");

        return $succeeded === $results->count() ? self::SUCCESS : self::FAILURE;
    }
}
