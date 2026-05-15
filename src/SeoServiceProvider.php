<?php

declare(strict_types=1);

namespace ProgrammerHasan\Seo;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use ProgrammerHasan\Seo\Commands\AuditCommand;
use ProgrammerHasan\Seo\Commands\InstallCommand;
use ProgrammerHasan\Seo\Commands\RobotsCommand;
use ProgrammerHasan\Seo\Commands\SitemapCommand;
use ProgrammerHasan\Seo\Components\Meta;
use ProgrammerHasan\Seo\Support\HtmlRenderer;

final class SeoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/seo.php', 'seo');

        $this->app->singleton('seo', function () {
            return new SeoManager(new HtmlRenderer);
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/seo.php' => config_path('seo.php'),
        ], 'seo-config');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/seo'),
        ], 'seo-views');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'seo');

        $this->loadViewComponentsAs('seo', [Meta::class]);

        // Supports: <x-seo />
        Blade::component('seo', Meta::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                SitemapCommand::class,
                RobotsCommand::class,
                AuditCommand::class,
            ]);
        }
    }
}