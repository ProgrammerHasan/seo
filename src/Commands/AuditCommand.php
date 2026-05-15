<?php

declare(strict_types=1);

namespace ProgrammerHasan\Seo\Commands;

use Illuminate\Console\Command;

final class AuditCommand extends Command
{
    protected $signature = 'seo:audit {url?}';

    protected $description = 'Run a simple SEO audit for a URL';

    public function handle(): int
    {
        $url = $this->argument('url') ?: url('/');
        $html = @file_get_contents($url);
        if (! $html) {
            $this->error('Unable to read URL: '.$url);

            return self::FAILURE;
        }
        $checks = [
            'title' => (bool) preg_match('/<title>.+<\/title>/is', $html),
            'description' => (bool) preg_match('/<meta[^>]+name=["\']description["\']/i', $html),
            'canonical' => (bool) preg_match('/<link[^>]+rel=["\']canonical["\']/i', $html),
            'open_graph' => (bool) preg_match('/property=["\']og:/i', $html),
            'json_ld' => (bool) preg_match('/application\/ld\+json/i', $html),
            'h1' => (bool) preg_match('/<h1[\s>]/i', $html),
        ];
        foreach ($checks as $name => $passed) {
            $this->line(($passed ? 'PASS ' : 'WARN ').$name);
        }

        return self::SUCCESS;
    }
}
