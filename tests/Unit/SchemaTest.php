<?php

declare(strict_types=1);

use ProgrammerHasan\Seo\Schema;

test('it builds product schema', function () {
    $schema = (new Schema())->product(
        name: 'SEO Toolkit',
        description: 'Laravel SEO package',
        image: 'https://example.com/product.jpg',
        sku: 'SEO-001',
        offers: (new Schema())->offer('49', 'USD'),
    );

    expect($schema)
        ->toHaveKey('@context', 'https://schema.org')
        ->toHaveKey('@type', 'Product')
        ->toHaveKey('name', 'SEO Toolkit')
        ->toHaveKey('offers');
});

test('it builds faq schema', function () {
    $schema = (new Schema())->faq([
        ['question' => 'What is this?', 'answer' => 'A Laravel SEO package.'],
    ]);

    expect($schema['@type'])->toBe('FAQPage')
        ->and($schema['mainEntity'][0]['@type'])->toBe('Question')
        ->and($schema['mainEntity'][0]['acceptedAnswer']['@type'])->toBe('Answer');
});
