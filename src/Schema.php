<?php

declare(strict_types=1);

namespace ProgrammerHasan\Seo;

final class Schema
{
    private function base(string $type, array $data = []): array
    {
        return array_filter(array_merge(['@context' => 'https://schema.org', '@type' => $type], $data), fn ($v) => $v !== null && $v !== [] && $v !== '');
    }

    public function organization(string $name, string $url, ?string $logo = null, array $sameAs = []): array { return $this->base('Organization', compact('name', 'url', 'logo', 'sameAs')); }
    public function website(string $name, string $url, ?string $searchUrl = null): array
    {
        return $this->base('WebSite', ['name' => $name, 'url' => $url, 'potentialAction' => $searchUrl ? ['@type' => 'SearchAction', 'target' => $searchUrl, 'query-input' => 'required name=search_term_string'] : null]);
    }
    public function person(string $name, ?string $url = null, ?string $image = null): array { return $this->base('Person', compact('name', 'url', 'image')); }
    public function article(string $headline, string $url, ?string $image = null, ?string $datePublished = null, ?string $dateModified = null, ?array $author = null): array { return $this->base('Article', compact('headline', 'url', 'image', 'datePublished', 'dateModified', 'author')); }
    public function blogPosting(string $headline, string $url, ?string $image = null, ?array $author = null): array { return $this->base('BlogPosting', compact('headline', 'url', 'image', 'author')); }
    public function newsArticle(string $headline, string $url, ?string $image = null, ?string $datePublished = null): array { return $this->base('NewsArticle', compact('headline', 'url', 'image', 'datePublished')); }
    public function product(string $name, ?string $description = null, ?string $image = null, ?string $sku = null, ?array $offers = null, ?array $aggregateRating = null): array { return $this->base('Product', compact('name', 'description', 'image', 'sku', 'offers', 'aggregateRating')); }
    public function offer(string $price, string $currency = 'USD', ?string $availability = 'https://schema.org/InStock', ?string $url = null): array { return $this->base('Offer', ['price' => $price, 'priceCurrency' => $currency, 'availability' => $availability, 'url' => $url]); }
    public function aggregateRating(float $ratingValue, int $reviewCount): array { return $this->base('AggregateRating', compact('ratingValue', 'reviewCount')); }
    public function review(string $body, array|string $author, int|float $rating): array { return $this->base('Review', ['reviewBody' => $body, 'author' => is_array($author) ? $author : $this->person($author), 'reviewRating' => ['@type' => 'Rating', 'ratingValue' => $rating]]); }
    public function event(string $name, string $startDate, ?string $location = null, ?string $url = null): array { return $this->base('Event', ['name' => $name, 'startDate' => $startDate, 'location' => $location ? ['@type' => 'Place', 'name' => $location] : null, 'url' => $url]); }
    public function course(string $name, string $description, ?string $provider = null): array { return $this->base('Course', ['name' => $name, 'description' => $description, 'provider' => $provider ? $this->organization($provider, url('/')) : null]); }
    public function jobPosting(string $title, string $description, string $datePosted, ?string $validThrough = null): array { return $this->base('JobPosting', compact('title', 'description', 'datePosted', 'validThrough')); }
    public function localBusiness(string $name, string $url, ?string $telephone = null, ?array $address = null): array { return $this->base('LocalBusiness', compact('name', 'url', 'telephone', 'address')); }
    public function videoObject(string $name, string $description, string $thumbnailUrl, string $uploadDate, ?string $contentUrl = null): array { return $this->base('VideoObject', compact('name', 'description', 'thumbnailUrl', 'uploadDate', 'contentUrl')); }
    public function imageObject(string $url, ?string $caption = null): array { return $this->base('ImageObject', compact('url', 'caption')); }
    public function softwareApplication(string $name, string $operatingSystem = 'Web', ?string $applicationCategory = null): array { return $this->base('SoftwareApplication', compact('name', 'operatingSystem', 'applicationCategory')); }
    public function breadcrumb(array $items): array
    {
        return $this->base('BreadcrumbList', ['itemListElement' => array_map(fn ($item, $i) => ['@type' => 'ListItem', 'position' => $i + 1, 'name' => $item['name'], 'item' => $item['url']], $items, array_keys($items))]);
    }
    public function faq(array $items): array
    {
        return $this->base('FAQPage', ['mainEntity' => array_map(fn ($item) => ['@type' => 'Question', 'name' => $item['question'], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['answer']]], $items)]);
    }
    public function howTo(string $name, array $steps): array { return $this->base('HowTo', ['name' => $name, 'step' => array_map(fn ($step) => is_array($step) ? $step : ['@type' => 'HowToStep', 'text' => $step], $steps)]); }
    public function recipe(string $name, array $ingredients, array $instructions): array { return $this->base('Recipe', ['name' => $name, 'recipeIngredient' => $ingredients, 'recipeInstructions' => $instructions]); }
}
