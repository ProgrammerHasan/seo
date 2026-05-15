<?php

declare(strict_types=1);

namespace ProgrammerHasan\Seo\Commands;

use Illuminate\Console\Command;
use ProgrammerHasan\Seo\Sitemap;

final class SitemapCommand extends Command
{
    protected $signature = 'seo:sitemap {--path= : Output path}';

    protected $description = 'Generate a basic sitemap.xml file';

    public function handle(): int
    {
        $path = $this->option('path') ?: public_path('sitemap.xml');
        (new Sitemap)->add(url('/'), now()->toAtomString())->save($path);
        $this->info("Sitemap generated: {$path}");

        return self::SUCCESS;
    }
}
