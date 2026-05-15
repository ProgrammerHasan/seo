# Robots.txt

```php
Seo::robots()
    ->allow('/')
    ->disallow('/admin')
    ->sitemap('https://example.com/sitemap.xml')
    ->save(public_path('robots.txt'));
```

Command:

```bash
php artisan seo:robots
```
