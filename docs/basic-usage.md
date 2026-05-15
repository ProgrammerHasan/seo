# Basic Usage

```php
use ProgrammerHasan\Seo\Facades\Seo;

Seo::title('Page title')
    ->description('Page description')
    ->canonical(url()->current())
    ->image(asset('og.jpg'));
```

Render tags:

```blade
{!! Seo::generate() !!}
```

Or:

```blade
<x-seo::meta />
```
