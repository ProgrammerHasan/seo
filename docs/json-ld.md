# JSON-LD Schema

```php
$schema = Seo::schema();

Seo::make()
    ->schema($schema->article('Post title', 'https://example.com/post'))
    ->generate();
```

Available helpers:

- Organization
- WebSite
- Person
- Article
- BlogPosting
- NewsArticle
- Product
- Offer
- AggregateRating
- Review
- FAQPage
- BreadcrumbList
- Event
- Course
- JobPosting
- LocalBusiness
- VideoObject
- ImageObject
- SoftwareApplication
- HowTo
- Recipe
