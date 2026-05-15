<?php

declare(strict_types=1);

namespace ProgrammerHasan\Seo\Ai\Drivers;

interface AiDriverInterface
{
    public function generate(string $content): array;

    public function improve(string $content): array;

    public function analyze(string $content, ?string $keyword = null): array;
}
