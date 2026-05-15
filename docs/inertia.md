# Inertia.js

```php
return Inertia::render('Posts/Show', [
    'post' => $post,
    'seo' => Seo::make()
        ->title($post->title)
        ->description($post->excerpt)
        ->canonical(route('posts.show', $post))
        ->forInertia(),
]);
```

React:

```jsx
import { Head } from '@inertiajs/react'

export default function Show({ seo }) {
  return (
    <Head>
      <title>{seo.title}</title>
      <meta name="description" content={seo.description} />
      <link rel="canonical" href={seo.canonical} />
    </Head>
  )
}
```
