<?php

declare(strict_types=1);

use ProgrammerHasan\Seo\Facades\Seo;

test('it renders basic meta, open graph and twitter tags', function () {
    $html = Seo::make()
        ->title('Laravel SEO')
        ->description('A lightweight Laravel SEO package.')
        ->canonical('https://example.com/seo')
        ->image('https://example.com/seo.jpg')
        ->generate();

    expect($html)
        ->toContain('<title>Laravel SEO</title>')
        ->toContain('<meta name="description" content="A lightweight Laravel SEO package.">')
        ->toContain('<link rel="canonical" href="https://example.com/seo">')
        ->toContain('<meta property="og:title" content="Laravel SEO">')
        ->toContain('<meta name="twitter:card" content="summary_large_image">');
});

test('it escapes html values', function () {
    $html = Seo::make()
        ->title('<SEO & Laravel>')
        ->description('Safe "description"')
        ->generate();

    expect($html)
        ->toContain('&lt;SEO &amp; Laravel&gt;')
        ->toContain('Safe &quot;description&quot;');
});
