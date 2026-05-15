<?php

declare(strict_types=1);

namespace ProgrammerHasan\Seo\Commands;

use Illuminate\Console\Command;
use ProgrammerHasan\Seo\Robots;

final class RobotsCommand extends Command
{
    protected $signature = 'seo:robots {--path= : Output path}';

    protected $description = 'Generate a robots.txt file';

    public function handle(): int
    {
        $path = $this->option('path') ?: public_path('robots.txt');
        (new Robots)->allow('/')->sitemap(url('/sitemap.xml'))->save($path);
        $this->info("Robots generated: {$path}");

        return self::SUCCESS;
    }
}
