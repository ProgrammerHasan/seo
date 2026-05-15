<?php

declare(strict_types=1);

namespace ProgrammerHasan\Seo\Facades;

use Illuminate\Support\Facades\Facade;

final class Seo extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'seo';
    }
}
