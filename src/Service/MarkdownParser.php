<?php

namespace App\Service;

use Parsedown;
class MarkdownParser
{
    private Parsedown $parsedown;

    public function __construct()
    {
        $this->parsedown = new Parsedown();
        $this->parsedown->setSafeMode(true);
    }

    public function toHtml(string $markdown): string
    {
        return $this->parsedown->text($markdown);
    }
}