<?php

declare(strict_types=1);

namespace ProgrammerHasan\Seo\Ai\Drivers;

class LocalDriver implements AiDriverInterface
{
    public function generate(string $content): array
    {
        $plain = trim(preg_replace('/\s+/', ' ', strip_tags($content)) ?? '');
        $title = mb_substr($plain, 0, 58) ?: config('seo.default_title');
        $description = mb_substr($plain, 0, 155) ?: config('seo.default_description');
        return ['title' => $title, 'description' => $description, 'keywords' => $this->keywords($plain), 'score' => $this->score($plain), 'driver' => 'local'];
    }
    public function improve(string $content): array
    {
        $data = $this->generate($content);
        $data['suggestions'] = ['Keep title near 50-60 characters.', 'Keep description near 140-160 characters.', 'Add a clear H1 and internal links.', 'Add JSON-LD schema when possible.'];
        return $data;
    }
    public function analyze(string $content, ?string $keyword = null): array
    {
        $plain = trim(preg_replace('/\s+/', ' ', strip_tags($content)) ?? '');
        $words = str_word_count($plain);
        return ['score' => $this->score($plain), 'word_count' => $words, 'keyword' => $keyword, 'keyword_density' => $keyword ? round(substr_count(mb_strtolower($plain), mb_strtolower($keyword)) / max(1, $words) * 100, 2) : null, 'readability' => $words > 300 ? 'good' : 'thin', 'driver' => 'local'];
    }
    private function keywords(string $text): array
    {
        $words = array_filter(str_word_count(mb_strtolower($text), 1), fn ($w) => mb_strlen($w) > 4);
        $freq = array_count_values($words); arsort($freq); return array_slice(array_keys($freq), 0, 8);
    }
    private function score(string $text): int { $len = mb_strlen($text); return min(100, 40 + ($len > 300 ? 30 : 0) + ($len > 800 ? 20 : 0) + (str_contains($text, '?') ? 10 : 0)); }
}
