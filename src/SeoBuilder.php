<?php

declare(strict_types=1);

namespace ProgrammerHasan\Seo;

use ProgrammerHasan\Seo\Data\SeoData;
use ProgrammerHasan\Seo\Support\HtmlRenderer;

final class SeoBuilder
{
    public function __construct(
        private readonly SeoData $data = new SeoData,
        private readonly ?HtmlRenderer $renderer = null,
    ) {}

    public static function make(?HtmlRenderer $renderer = null): self
    {
        return new self(SeoData::make(), $renderer);
    }

    public function title(string $title): self
    {
        $this->data->title($title);

        return $this;
    }

    public function description(string $description): self
    {
        $this->data->description($description);

        return $this;
    }

    public function keywords(array|string $keywords): self
    {
        $this->data->meta('keywords', is_array($keywords) ? implode(', ', $keywords) : $keywords);

        return $this;
    }

    public function canonical(?string $url = null): self
    {
        $this->data->canonical($url ?: url()->current());

        return $this;
    }

    public function autoCanonical(): self
    {
        $this->data->canonical(UrlCanonicalizer::current());

        return $this;
    }

    public function image(string $url, ?string $alt = null, ?int $width = null, ?int $height = null): self
    {
        $this->data->image($url);
        $this->data->og('image', $url);
        if ($alt) {
            $this->data->og('image:alt', $alt);
        }
        if ($width) {
            $this->data->og('image:width', $width);
        }
        if ($height) {
            $this->data->og('image:height', $height);
        }

        return $this;
    }

    public function type(string $type): self
    {
        $this->data->type($type);

        return $this;
    }

    public function article(?string $published = null, ?string $modified = null, array $tags = []): self
    {
        $this->data->type('article');
        if ($published) {
            $this->data->og('article:published_time', $published);
        }
        if ($modified) {
            $this->data->og('article:modified_time', $modified);
        }
        foreach ($tags as $tag) {
            $this->data->og('article:tag', $tag);
        }

        return $this;
    }

    public function noIndex(): self
    {
        $this->data->noIndex();

        return $this;
    }

    public function noFollow(): self
    {
        $this->data->noFollow();

        return $this;
    }

    public function alternate(string $locale, string $url): self
    {
        $this->data->alternate($locale, $url);

        return $this;
    }

    public function hreflang(string $locale, string $url): self
    {
        return $this->alternate($locale, $url);
    }

    public function og(string $key, mixed $value): self
    {
        $this->data->og($key, $value);

        return $this;
    }

    public function twitter(string $key, mixed $value): self
    {
        $this->data->twitter($key, $value);

        return $this;
    }

    public function schema(array $schema): self
    {
        $this->data->schema($schema);

        return $this;
    }

    public function preload(string $href, string $as = 'style', ?string $type = null, bool $crossorigin = false): self
    {
        $this->data->preload($href, $as, $type, $crossorigin);

        return $this;
    }

    public function preconnect(string $href, bool $crossorigin = false): self
    {
        $this->data->preconnect($href, $crossorigin);

        return $this;
    }

    public function dnsPrefetch(string $href): self
    {
        $this->data->dnsPrefetch($href);

        return $this;
    }

    public function pagination(mixed $paginator): self
    {
        if (method_exists($paginator, 'previousPageUrl') && $paginator->previousPageUrl()) {
            $this->data->link('prev', $paginator->previousPageUrl());
        }
        if (method_exists($paginator, 'nextPageUrl') && $paginator->nextPageUrl()) {
            $this->data->link('next', $paginator->nextPageUrl());
        }

        return $this;
    }

    public function data(): SeoData
    {
        return $this->data;
    }

    public function toArray(): array
    {
        return $this->data->toArray();
    }

    public function forInertia(): array
    {
        return $this->toArray();
    }

    public function generate(): string
    {
        return $this->toHtml();
    }

    public function toHtml(): string
    {
        return ($this->renderer ?: new HtmlRenderer)->render($this->data);
    }
}
