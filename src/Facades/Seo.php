<?php

declare(strict_types=1);

namespace ProgrammerHasan\Seo\Facades;

use Illuminate\Support\Facades\Facade;
use ProgrammerHasan\Seo\Ai\SeoAi;
use ProgrammerHasan\Seo\Schema;
use ProgrammerHasan\Seo\SeoBuilder;

/**
 * @method static SeoBuilder make()
 * @method static SeoAi ai()
 * @method static string toHtml()
 * @method static string generate()
 * @method static Schema schema()
 * @method static SeoBuilder title(string $title)
 * @method static SeoBuilder description(string $description)
 * @method static SeoBuilder canonical(?string $url = null)
 * @method static SeoBuilder image(string $url, ?string $alt = null)
 */
final class Seo extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'seo';
    }
}
