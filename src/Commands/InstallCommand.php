<?php

declare(strict_types=1);

namespace ProgrammerHasan\Seo\Commands;

use Illuminate\Console\Command;

final class InstallCommand extends Command
{
    protected $signature = 'seo:install';
    protected $description = 'Publish SEO configuration file';

    public function handle(): int
    {
        $this->call('vendor:publish', [
            '--tag' => 'seo-config',
            '--force' => true,
        ]);

        $this->info('SEO installed successfully.');
        return self::SUCCESS;
    }
}
