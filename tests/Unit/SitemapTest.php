<?php

declare(strict_types=1);

use ProgrammerHasan\Seo\Sitemap;

test('it builds sitemap xml', function () {
    $xml = (new Sitemap())
        ->add('https://example.com', '2026-01-01', 'daily', 1.0)
        ->image('https://example.com/post', ['https://example.com/image.jpg'])
        ->toXml();

    expect($xml)
        ->toContain('<urlset')
        ->toContain('<loc>https://example.com</loc>')
        ->toContain('<image:loc>https://example.com/image.jpg</image:loc>');
});

test('it builds sitemap index xml', function () {
    $xml = (new Sitemap())
        ->index('https://example.com/sitemap-posts.xml')
        ->toXml();

    expect($xml)
        ->toContain('<sitemapindex')
        ->toContain('<loc>https://example.com/sitemap-posts.xml</loc>');
});
