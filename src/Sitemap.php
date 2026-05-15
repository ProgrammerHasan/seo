<?php

declare(strict_types=1);

namespace ProgrammerHasan\Seo;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;

final class Sitemap
{
    private array $urls = [];

    private array $sitemaps = [];

    public function add(
        string $loc,
        ?string $lastmod = null,
        ?string $changefreq = null,
        ?float $priority = null,
        array $images = [],
        array $videos = [],
        ?array $news = null
    ): self {
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

        if (is_string($models)) {
            return $this;
        }

        foreach ($models as $model) {
            if (! is_object($model) || ! method_exists($model, $urlMethod)) {
                continue;
            }

            $this->add(
                loc: (string) $model->{$urlMethod}(),
                lastmod: $this->getModelLastModified($model),
            );
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
        if ($this->sitemaps !== []) {
            return $this->indexXml();
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL
            .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" '
            .'xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" '
            .'xmlns:video="http://www.google.com/schemas/sitemap-video/1.1" '
            .'xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">'.PHP_EOL;

        foreach ($this->urls as $url) {
            $xml .= '  <url>'.PHP_EOL;
            $xml .= '    <loc>'.$this->e((string) $url['loc']).'</loc>'.PHP_EOL;

            foreach (['lastmod', 'changefreq', 'priority'] as $key) {
                if ($url[$key] !== null) {
                    $xml .= '    <'.$key.'>'.$this->e((string) $url[$key]).'</'.$key.'>'.PHP_EOL;
                }
            }

            foreach ($url['images'] as $image) {
                $imageLoc = is_array($image) ? (string) ($image['loc'] ?? '') : (string) $image;

                if ($imageLoc === '') {
                    continue;
                }

                $xml .= '    <image:image><image:loc>'.$this->e($imageLoc).'</image:loc></image:image>'.PHP_EOL;
            }

            foreach ($url['videos'] as $video) {
                if (! is_array($video)) {
                    continue;
                }

                $xml .= '    <video:video>';
                $xml .= '<video:title>'.$this->e((string) ($video['title'] ?? '')).'</video:title>';
                $xml .= '<video:thumbnail_loc>'.$this->e((string) ($video['thumbnail'] ?? '')).'</video:thumbnail_loc>';
                $xml .= '</video:video>'.PHP_EOL;
            }

            if (is_array($url['news'])) {
                $xml .= '    <news:news>';
                $xml .= '<news:publication>';
                $xml .= '<news:name>'.$this->e((string) ($url['news']['name'] ?? config('seo.site_name', ''))).'</news:name>';
                $xml .= '<news:language>'.$this->e((string) ($url['news']['language'] ?? 'en')).'</news:language>';
                $xml .= '</news:publication>';
                $xml .= '<news:title>'.$this->e((string) ($url['news']['title'] ?? '')).'</news:title>';
                $xml .= '</news:news>'.PHP_EOL;
            }

            $xml .= '  </url>'.PHP_EOL;
        }

        return $xml.'</urlset>'.PHP_EOL;
    }

    public function save(string $path): bool
    {
        return (bool) file_put_contents($path, $this->toXml());
    }

    private function indexXml(): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL
            .'<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;

        foreach ($this->sitemaps as $sitemap) {
            $xml .= '  <sitemap><loc>'.$this->e((string) $sitemap['loc']).'</loc>';

            if ($sitemap['lastmod'] !== null) {
                $xml .= '<lastmod>'.$this->e((string) $sitemap['lastmod']).'</lastmod>';
            }

            $xml .= '</sitemap>'.PHP_EOL;
        }

        return $xml.'</sitemapindex>'.PHP_EOL;
    }

    private function getModelLastModified(object $model): ?string
    {
        if (! $model instanceof Model) {
            return null;
        }

        $updatedAt = $model->getAttribute('updated_at');

        if ($updatedAt instanceof CarbonInterface) {
            return $updatedAt->toAtomString();
        }

        return $updatedAt !== null ? (string) $updatedAt : null;
    }

    private function e(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }
}
