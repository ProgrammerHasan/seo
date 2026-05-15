<?php

declare(strict_types=1);

namespace ProgrammerHasan\Seo\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use ProgrammerHasan\Seo\SeoServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [SeoServiceProvider::class];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.url', 'https://example.com');
        $app['config']->set('seo.default_title', 'Example');
        $app['config']->set('seo.default_description', 'Default description');
        $app['config']->set('seo.default_image', 'https://example.com/og.jpg');
        $app['config']->set('seo.site_name', 'Example');
        $app['config']->set('seo.ai.default_driver', 'local');
    }
}
