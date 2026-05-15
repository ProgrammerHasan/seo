# Optional AI SEO

AI features are optional.

Cloud drivers need API keys:

```env
SEO_AI_DRIVER=openai
SEO_OPENAI_API_KEY=your-api-key
```

```env
SEO_AI_DRIVER=gemini
SEO_GEMINI_API_KEY=your-api-key
```

Ollama/local does not need a cloud API key:

```env
SEO_AI_DRIVER=ollama
SEO_OLLAMA_URL=http://localhost:11434
SEO_OLLAMA_MODEL=llama3
```

Usage:

```php
Seo::ai()->generate($content);
Seo::ai()->improve($content);
Seo::ai()->analyze($content, 'target keyword');
```
