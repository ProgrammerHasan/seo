# Sitemap

```php
Seo::sitemap()
    ->add('https://example.com')
    ->add('https://example.com/about')
    ->save(public_path('sitemap.xml'));
```

Image sitemap:

```php
Seo::sitemap()->image('https://example.com/post', [
    'https://example.com/image.jpg',
]);
```

Sitemap index:

```php
Seo::sitemap()
    ->index('https://example.com/sitemap-posts.xml')
    ->index('https://example.com/sitemap-products.xml')
    ->save(public_path('sitemap.xml'));
```
