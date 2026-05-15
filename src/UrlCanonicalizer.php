<?php

declare(strict_types=1);

namespace ProgrammerHasan\Seo;

final class UrlCanonicalizer
{
    public static function current(array $ignored = ['utm_source','utm_medium','utm_campaign','utm_term','utm_content','fbclid','gclid']): string
    {
        if (!function_exists('request')) return '';
        $request = request();
        $query = $request->query();
        foreach ($ignored as $key) unset($query[$key]);
        ksort($query);
        $base = $request->url();
        return $query ? $base.'?'.http_build_query($query) : $base;
    }
}
