<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use App\Service\MarkdownParser;
class MarkdownExtension extends AbstractExtension
{
    private MarkdownParser $markdownParser;

    public function __construct(MarkdownParser $markdownParser)
    {
        $this->markdownParser = $markdownParser;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('markdown', [$this, 'parseMarkdown'], ['is_safe' => ['html']]),
        ];
    }

    public function parseMarkdown(string $content): string
    {
        return $this->markdownParser->toHtml($content);
    }
}