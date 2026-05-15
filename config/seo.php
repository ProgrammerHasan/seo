<?php

declare(strict_types=1);

return [
    'site_name' => env('APP_NAME', 'Laravel'),
    'default_title' => env('SEO_DEFAULT_TITLE', env('APP_NAME', 'Laravel')),
    'default_description' => env('SEO_DEFAULT_DESCRIPTION', ''),
    'default_image' => env('SEO_DEFAULT_IMAGE', null),
    'twitter_site' => env('SEO_TWITTER_SITE', null),
    'twitter_creator' => env('SEO_TWITTER_CREATOR', null),
    'robots' => [
        'index' => env('SEO_ROBOTS_INDEX', true),
        'follow' => env('SEO_ROBOTS_FOLLOW', true),
    ],
    'ai' => [
        'default_driver' => env('SEO_AI_DRIVER', 'local'),
        'openai' => ['api_key' => env('OPENAI_API_KEY'), 'model' => env('SEO_OPENAI_MODEL', 'gpt-4o-mini')],
        'gemini' => ['api_key' => env('GEMINI_API_KEY'), 'model' => env('SEO_GEMINI_MODEL', 'gemini-1.5-flash')],
        'ollama' => ['base_url' => env('OLLAMA_BASE_URL', 'http://localhost:11434'), 'model' => env('SEO_OLLAMA_MODEL', 'llama3')],
    ],
];
