<?php

declare(strict_types=1);

namespace ProgrammerHasan\Seo\Contracts;

use ProgrammerHasan\Seo\Data\SeoData;

interface Seoable
{
    public function toSeo(): SeoData;
}
