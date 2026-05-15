<?php

declare(strict_types=1);

namespace ProgrammerHasan\Seo\Concerns;

use ProgrammerHasan\Seo\Data\SeoData;

/**
 * @phpstan-ignore trait.unused
 */
trait HasSeo
{
    public function toSeo(): SeoData
    {
        return SeoData::make()
            ->title((string) ($this->seo_title ?? $this->title ?? $this->name ?? config('seo.default_title')))
            ->description((string) ($this->seo_description ?? $this->description ?? $this->excerpt ?? config('seo.default_description')))
            ->canonical(method_exists($this, 'url') ? $this->url() : url()->current())
            ->image((string) ($this->seo_image ?? $this->image_url ?? $this->image ?? config('seo.default_image')));
    }
}
