<?php

declare(strict_types=1);

namespace ProgrammerHasan\Seo;

final class Sitemap
{
    private array $urls = [];

    private array $sitemaps = [];

    public function add(string $loc, ?string $lastmod = null, ?string $changefreq = null, ?float $priority = null, array $images = [], array $videos = [], ?array $news = null): self
    {
        $this->urls[] = compact('loc', 'lastmod', 'changefreq', 'priority', 'images', 'videos', 'news');

        return $this;
    }

    public function addRoute(string $name, array $params = [], ?string $lastmod = null): self
    {
        return $this->add(route($name, $params), $lastmod);
    }

    public function addModel(iterable|string $models, string $urlMethod = 'url'): self
    {
        if (is_string($models) && class_exists($models) && method_exists($models, 'query')) {
            $models = $models::query()->get();
        }
        foreach ($models as $model) {
            if (method_exists($model, $urlMethod)) {
                $this->add($model->{$urlMethod}(), method_exists($model, 'getAttribute') ? optional($model->updated_at)->toAtomString() : null);
            }
        }

        return $this;
    }

    public function image(string $loc, array $images): self
    {
        return $this->add($loc, images: $images);
    }

    public function video(string $loc, array $videos): self
    {
        return $this->add($loc, videos: $videos);
    }

    public function news(string $loc, array $news): self
    {
        return $this->add($loc, news: $news);
    }

    public function index(string $loc, ?string $lastmod = null): self
    {
        $this->sitemaps[] = compact('loc', 'lastmod');

        return $this;
    }

    public function toXml(): string
    {
        if ($this->sitemaps) {
            return $this->indexXml();
        }
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL.'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">'.PHP_EOL;
        foreach ($this->urls as $url) {
            $xml .= '  <url>'.PHP_EOL.'    <loc>'.$this->e($url['loc']).'</loc>'.PHP_EOL;
            foreach (['lastmod', 'changefreq', 'priority'] as $key) {
                if ($url[$key] !== null) {
                    $xml .= '    <'.$key.'>'.$this->e((string) $url[$key]).'</'.$key.'>'.PHP_EOL;
                }
            }
            foreach ($url['images'] as $image) {
                $xml .= '    <image:image><image:loc>'.$this->e(is_array($image) ? $image['loc'] : $image).'</image:loc></image:image>'.PHP_EOL;
            }
            foreach ($url['videos'] as $video) {
                $xml .= '    <video:video><video:title>'.$this->e($video['title'] ?? '').'</video:title><video:thumbnail_loc>'.$this->e($video['thumbnail'] ?? '').'</video:thumbnail_loc></video:video>'.PHP_EOL;
            }
            if ($url['news']) {
                $xml .= '    <news:news><news:publication><news:name>'.$this->e($url['news']['name'] ?? config('seo.site_name')).'</news:name><news:language>'.$this->e($url['news']['language'] ?? 'en').'</news:language></news:publication><news:title>'.$this->e($url['news']['title'] ?? '').'</news:title></news:news>'.PHP_EOL;
            }
            $xml .= '  </url>'.PHP_EOL;
        }

        return $xml.'</urlset>'.PHP_EOL;
    }

    private function indexXml(): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL.'<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;
        foreach ($this->sitemaps as $sitemap) {
            $xml .= '  <sitemap><loc>'.$this->e($sitemap['loc']).'</loc>';
            if ($sitemap['lastmod']) {
                $xml .= '<lastmod>'.$this->e($sitemap['lastmod']).'</lastmod>';
            } $xml .= '</sitemap>'.PHP_EOL;
        }

        return $xml.'</sitemapindex>'.PHP_EOL;
    }

    public function save(string $path): bool
    {
        return (bool) file_put_contents($path, $this->toXml());
    }

    private function e(string $v): string
    {
        return htmlspecialchars($v, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }
}
