<?php

declare(strict_types=1);

namespace ProgrammerHasan\Seo;

final class Robots
{
    private array $lines = ['User-agent: *'];

    public function userAgent(string $agent): self
    {
        $this->lines[] = 'User-agent: '.$agent;

        return $this;
    }

    public function allow(string $path): self
    {
        $this->lines[] = 'Allow: '.$path;

        return $this;
    }

    public function disallow(string $path): self
    {
        $this->lines[] = 'Disallow: '.$path;

        return $this;
    }

    public function sitemap(string $url): self
    {
        $this->lines[] = 'Sitemap: '.$url;

        return $this;
    }

    public function crawlDelay(int $seconds): self
    {
        $this->lines[] = 'Crawl-delay: '.$seconds;

        return $this;
    }

    public function toText(): string
    {
        return implode(PHP_EOL, $this->lines).PHP_EOL;
    }

    public function save(string $path): bool
    {
        return (bool) file_put_contents($path, $this->toText());
    }
}
