<?php

declare(strict_types=1);

namespace ProgrammerHasan\Seo;

use ProgrammerHasan\Seo\Data\SeoData;

final class InertiaSeo
{
    public function __invoke(SeoData|SeoBuilder|array $seo): array
    {
        if ($seo instanceof SeoBuilder) return $seo->forInertia();
        if ($seo instanceof SeoData) return $seo->toArray();
        return $seo;
    }

    public function head(SeoData|SeoBuilder|array $seo): array
    {
        $data = $this($seo);
        return [
            'title' => $data['title'] ?? config('seo.default_title'),
            'meta' => [
                'description' => $data['description'] ?? config('seo.default_description'),
                'robots' => (($data['index'] ?? true) ? 'index' : 'noindex').', '.(($data['follow'] ?? true) ? 'follow' : 'nofollow'),
            ],
            'canonical' => $data['canonical'] ?? null,
            'openGraph' => $data['openGraph'] ?? [],
            'twitter' => $data['twitter'] ?? [],
            'jsonLd' => $data['jsonLd'] ?? [],
            'hydration' => true,
        ];
    }
}
