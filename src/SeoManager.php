<?php

declare(strict_types=1);

namespace ProgrammerHasan\Seo;

use Illuminate\Contracts\Support\Htmlable;
use ProgrammerHasan\Seo\Ai\SeoAi;
use ProgrammerHasan\Seo\Contracts\Seoable;
use ProgrammerHasan\Seo\Data\SeoData;
use ProgrammerHasan\Seo\Support\HtmlRenderer;

final class SeoManager implements Htmlable
{
    private SeoData $data;

    public function __construct(private readonly HtmlRenderer $renderer)
    {
        $this->data = SeoData::make();
    }

    public function make(): SeoBuilder
    {
        $this->data = SeoData::make();
        return new SeoBuilder($this->data, $this->renderer);
    }

    public function set(SeoData|SeoBuilder|array $data): self
    {
        if ($data instanceof SeoBuilder) $data = $data->data();
        $this->data = is_array($data) ? new SeoData(...$data) : $data;
        return $this;
    }

    public function fromModel(object $model): self
    {
        if ($model instanceof Seoable || method_exists($model, 'toSeo')) return $this->set($model->toSeo());
        return $this->set(SeoData::make()
            ->title((string) ($model->seo_title ?? $model->title ?? $model->name ?? config('seo.default_title')))
            ->description((string) ($model->seo_description ?? $model->description ?? $model->excerpt ?? config('seo.default_description')))
            ->canonical(method_exists($model, 'url') ? $model->url() : url()->current())
            ->image((string) ($model->seo_image ?? $model->image_url ?? $model->image ?? config('seo.default_image'))));
    }

    public function title(string $title): self { $this->data->title($title); return $this; }
    public function description(string $description): self { $this->data->description($description); return $this; }
    public function keywords(array|string $keywords): self { $this->data->meta('keywords', is_array($keywords) ? implode(', ', $keywords) : $keywords); return $this; }
    public function canonical(?string $url = null): self { $this->data->canonical($url ?: UrlCanonicalizer::current()); return $this; }
    public function autoCanonical(): self { $this->data->canonical(UrlCanonicalizer::current()); return $this; }
    public function image(string $url): self { $this->data->image($url); $this->data->og('image', $url); return $this; }
    public function type(string $type): self { $this->data->type($type); return $this; }
    public function noIndex(): self { $this->data->noIndex(); return $this; }
    public function noFollow(): self { $this->data->noFollow(); return $this; }
    public function alternate(string $locale, string $url): self { $this->data->alternate($locale, $url); return $this; }
    public function hreflang(string $locale, string $url): self { return $this->alternate($locale, $url); }
    public function og(string $key, mixed $value): self { $this->data->og($key, $value); return $this; }
    public function twitter(string $key, mixed $value): self { $this->data->twitter($key, $value); return $this; }
    public function schema(?array $schema = null): self|Schema { if ($schema === null) return new Schema(); $this->data->schema($schema); return $this; }
    public function preload(string $href, string $as = 'style', ?string $type = null, bool $crossorigin = false): self { $this->data->preload($href, $as, $type, $crossorigin); return $this; }
    public function preconnect(string $href, bool $crossorigin = false): self { $this->data->preconnect($href, $crossorigin); return $this; }
    public function dnsPrefetch(string $href): self { $this->data->dnsPrefetch($href); return $this; }
    public function pagination(mixed $paginator): self
    {
        if (method_exists($paginator, 'previousPageUrl') && $paginator->previousPageUrl()) $this->data->link('prev', $paginator->previousPageUrl());
        if (method_exists($paginator, 'nextPageUrl') && $paginator->nextPageUrl()) $this->data->link('next', $paginator->nextPageUrl());
        return $this;
    }

    public function schemaBuilder(): Schema { return new Schema(); }
    public function schemaFactory(): Schema { return new Schema(); }
    public function ai(): SeoAi { return new SeoAi((string) config('seo.ai.default_driver', 'local')); }
    public function inertia(SeoData|SeoBuilder|array|null $seo = null): array { return (new InertiaSeo())->head($seo ?: $this->data); }
    public function sitemap(): Sitemap { return new Sitemap(); }
    public function robots(): Robots { return new Robots(); }

    public function toHtml(): string { return $this->renderer->render($this->data); }
    public function generate(): string { return $this->toHtml(); }
    public function toArray(): array { return $this->data->toArray(); }
    public function forInertia(): array { return (new InertiaSeo())($this->data); }
}
