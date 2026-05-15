# Configuration

The package config lives in `config/seo.php` after publishing.

Important options:

```php
return [
    'default_title' => env('APP_NAME'),
    'default_description' => null,
    'default_image' => null,
    'site_name' => env('APP_NAME'),
    'twitter_site' => null,
    'twitter_creator' => null,

    'robots' => [
        'index' => true,
        'follow' => true,
    ],

    'ai' => [
        'default_driver' => env('SEO_AI_DRIVER', 'local'),
    ],
];
```

Use `.env` for sensitive values such as AI API keys.
