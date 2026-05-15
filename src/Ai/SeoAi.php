<?php

declare(strict_types=1);

namespace ProgrammerHasan\Seo\Ai;

final class SeoAi
{
    public function __construct(private string $driver = 'local') {}

    public function driver(string $driver): self
    {
        $this->driver = $driver;

        return $this;
    }

    public function generate(string $content): array
    {
        return $this->client()->generate($content);
    }

    public function improve(string $content): array
    {
        return $this->client()->improve($content);
    }

    public function analyze(string $content, ?string $keyword = null): array
    {
        return $this->client()->analyze($content, $keyword);
    }

    private function client(): Drivers\AiDriverInterface
    {
        return match ($this->driver) {
            'openai' => new Drivers\OpenAiDriver, 'gemini' => new Drivers\GeminiDriver, 'ollama' => new Drivers\OllamaDriver, default => new Drivers\LocalDriver,
        };
    }
}
