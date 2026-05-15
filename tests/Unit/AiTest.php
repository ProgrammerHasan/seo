<?php

declare(strict_types=1);

use ProgrammerHasan\Seo\Facades\Seo;

test('local ai driver returns seo suggestions without api key', function () {
    $result = Seo::ai()->generate('Laravel SEO package for meta tags, schema, sitemap and Inertia apps.');

    expect($result)
        ->toHaveKeys(['title', 'description', 'keywords'])
        ->and($result['title'])->toBeString();
});
