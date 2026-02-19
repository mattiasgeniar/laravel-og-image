<?php

namespace Spatie\OgImage;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\OgImage\Commands\ClearOgImagesCommand;
use Spatie\OgImage\Components\OgImageComponent;
use Spatie\OgImage\Http\Controllers\OgImageController;
use Spatie\OgImage\Http\Middleware\RenderOgImageMiddleware;

class OgImageServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('og-image')
            ->hasConfigFile()
            ->hasViews()
            ->hasCommand(ClearOgImagesCommand::class);
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(OgImage::class);
        $this->app->singleton(OgImageGenerator::class);
    }

    public function packageBooted(): void
    {
        Blade::component('og-image', OgImageComponent::class);

        $this->registerRoutes();
        $this->registerMiddleware();
    }

    protected function registerRoutes(): void
    {
        Route::prefix('og-image')
            ->group(function () {
                Route::get('{filename}', OgImageController::class)
                    ->where('filename', '[a-f0-9]+\.(png|jpeg|jpg|webp)')
                    ->name('og-image.serve');
            });
    }

    protected function registerMiddleware(): void
    {
        $router = $this->app['router'];

        $router->pushMiddlewareToGroup('web', RenderOgImageMiddleware::class);
    }
}
