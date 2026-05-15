<?php

declare(strict_types=1);

namespace ProgrammerHasan\Seo\Data;

final class SeoData
{
    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
        public ?string $canonical = null,
        public ?string $image = null,
        public string $type = 'website',
        public array $openGraph = [],
        public array $twitter = [],
        public array $jsonLd = [],
        public array $alternates = [],
        public array $meta = [],
        public array $links = [],
        public array $preloads = [],
        public array $preconnects = [],
        public array $dnsPrefetch = [],
        public ?bool $index = null,
        public ?bool $follow = null,
    ) {}

    public static function make(): self
    {
        return new self;
    }

    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function description(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function canonical(string $canonical): self
    {
        $this->canonical = $canonical;

        return $this;
    }

    public function image(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function type(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function noIndex(): self
    {
        $this->index = false;

        return $this;
    }

    public function noFollow(): self
    {
        $this->follow = false;

        return $this;
    }

    public function alternate(string $locale, string $url): self
    {
        $this->alternates[$locale] = $url;

        return $this;
    }

    public function og(string $key, mixed $value): self
    {
        $this->openGraph[$key] = $value;

        return $this;
    }

    public function twitter(string $key, mixed $value): self
    {
        $this->twitter[$key] = $value;

        return $this;
    }

    public function meta(string $name, mixed $value): self
    {
        $this->meta[$name] = $value;

        return $this;
    }

    public function link(string $rel, string $href, array $attributes = []): self
    {
        $this->links[] = ['rel' => $rel, 'href' => $href, 'attributes' => $attributes];

        return $this;
    }

    public function preload(string $href, string $as, ?string $type = null, bool $crossorigin = false): self
    {
        $this->preloads[] = compact('href', 'as', 'type', 'crossorigin');

        return $this;
    }

    public function preconnect(string $href, bool $crossorigin = false): self
    {
        $this->preconnects[] = compact('href', 'crossorigin');

        return $this;
    }

    public function dnsPrefetch(string $href): self
    {
        $this->dnsPrefetch[] = $href;

        return $this;
    }

    public function schema(array $schema): self
    {
        $this->jsonLd[] = $schema;

        return $this;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
