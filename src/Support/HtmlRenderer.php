<?php

declare(strict_types=1);

namespace ProgrammerHasan\Seo\Support;

use ProgrammerHasan\Seo\Data\SeoData;

final class HtmlRenderer
{
    public function render(SeoData $seo): string
    {
        $title = $seo->title ?: config('seo.default_title');
        $description = $seo->description ?: config('seo.default_description');
        $image = $seo->image ?: config('seo.default_image');
        $siteName = config('seo.site_name');
        $robots = $this->robots($seo);

        $tags = [];
        $tags[] = '<title>'.$this->e($title).'</title>';
        if ($description) {
            $tags[] = '<meta name="description" content="'.$this->e($description).'">';
        }
        $tags[] = '<meta name="robots" content="'.$this->e($robots).'">';
        if ($seo->canonical) {
            $tags[] = '<link rel="canonical" href="'.$this->e($seo->canonical).'">';
        }

        foreach ($seo->meta as $name => $value) {
            if ($value !== null && $value !== '') {
                $tags[] = '<meta name="'.$this->e((string) $name).'" content="'.$this->e((string) $value).'">';
            }
        }

        foreach ($seo->links as $link) {
            $tags[] = '<link rel="'.$this->e($link['rel']).'" href="'.$this->e($link['href']).'"'.$this->attrs($link['attributes'] ?? []).'>';
        }

        foreach ($seo->preloads as $preload) {
            $attrs = ['as' => $preload['as']];
            if ($preload['type']) {
                $attrs['type'] = $preload['type'];
            }
            if ($preload['crossorigin']) {
                $attrs['crossorigin'] = 'anonymous';
            }
            $tags[] = '<link rel="preload" href="'.$this->e($preload['href']).'"'.$this->attrs($attrs).'>';
        }

        foreach ($seo->preconnects as $preconnect) {
            $tags[] = '<link rel="preconnect" href="'.$this->e($preconnect['href']).'"'.($preconnect['crossorigin'] ? ' crossorigin' : '').'>';
        }

        foreach ($seo->dnsPrefetch as $href) {
            $tags[] = '<link rel="dns-prefetch" href="'.$this->e($href).'">';
        }

        foreach ($seo->alternates as $locale => $url) {
            $tags[] = '<link rel="alternate" hreflang="'.$this->e($locale).'" href="'.$this->e($url).'">';
        }

        $og = array_merge([
            'title' => $title,
            'description' => $description,
            'type' => $seo->type,
            'url' => $seo->canonical,
            'image' => $image,
            'site_name' => $siteName,
        ], $seo->openGraph);

        foreach ($og as $key => $value) {
            if ($value !== null && $value !== '') {
                $tags[] = '<meta property="og:'.$this->e((string) $key).'" content="'.$this->e((string) $value).'">';
            }
        }

        $twitter = array_merge([
            'card' => $image ? 'summary_large_image' : 'summary',
            'title' => $title,
            'description' => $description,
            'image' => $image,
            'site' => config('seo.twitter_site'),
            'creator' => config('seo.twitter_creator'),
        ], $seo->twitter);

        foreach ($twitter as $key => $value) {
            if ($value !== null && $value !== '') {
                $tags[] = '<meta name="twitter:'.$this->e((string) $key).'" content="'.$this->e((string) $value).'">';
            }
        }

        foreach ($seo->jsonLd as $schema) {
            $tags[] = '<script type="application/ld+json">'.json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE).'</script>';
        }

        return implode(PHP_EOL, $tags);
    }

    private function robots(SeoData $seo): string
    {
        $index = $seo->index ?? (bool) config('seo.robots.index', true);
        $follow = $seo->follow ?? (bool) config('seo.robots.follow', true);

        return ($index ? 'index' : 'noindex').', '.($follow ? 'follow' : 'nofollow');
    }

    private function attrs(array $attrs): string
    {
        $html = '';
        foreach ($attrs as $key => $value) {
            if ($value === true) {
                $html .= ' '.$this->e((string) $key);
            } elseif ($value !== false && $value !== null) {
                $html .= ' '.$this->e((string) $key).'="'.$this->e((string) $value).'"';
            }
        }

        return $html;
    }

    private function e(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
