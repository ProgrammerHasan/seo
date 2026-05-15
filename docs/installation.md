# Installation

Install the package with Composer:

```bash
composer require programmerhasan/seo
```

Publish the config file:

```bash
php artisan vendor:publish --tag=seo-config
```

Publish the views only when you want to customize the Blade component:

```bash
php artisan vendor:publish --tag=seo-views
```

Laravel auto-discovery registers the service provider and facade automatically.
