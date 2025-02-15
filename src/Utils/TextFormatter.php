<?php

namespace App\Utils;

class TextFormatter
{
    public static function formatTextToHtml(string $text): string
    {
        $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

        /* Gestion des titres avec Tailwind */
        $text = preg_replace('/^# (.+)$/m', '<h1 class="text-4xl font-bold mt-6 mb-4">$1</h1>', $text);
        $text = preg_replace('/^## (.+)$/m', '<h2 class="text-3xl font-semibold mt-5 mb-3">$1</h2>', $text);
        $text = preg_replace('/^### (.+)$/m', '<h3 class="text-2xl font-medium mt-4 mb-2">$1</h3>', $text);
        $text = preg_replace('/^#### (.+)$/m', '<h4 class="text-xl font-medium mt-3 mb-2">$1</h4>', $text);
        $text = preg_replace('/^##### (.+)$/m', '<h5 class="text-lg font-medium mt-2 mb-1">$1</h5>', $text);
        $text = preg_replace('/^###### (.+)$/m', '<h6 class="text-base font-medium mt-2 mb-1">$1</h6>', $text);

        /* Gestion des citations */
        $text = preg_replace('/^> (.+)$/m', '<blockquote class="border-l-4 border-gray-400 pl-4 italic text-gray-600">$1</blockquote>', $text);

        /* Gestion des mises en forme */
        $text = preg_replace('/\*\*(.*?)\*\*/', '<strong class="font-bold">$1</strong>', $text);
        $text = preg_replace('/\*(.*?)\*/', '<em class="italic">$1</em>', $text);
        $text = preg_replace('/__(.*?)__/', '<u class="underline">$1</u>', $text);
        $text = preg_replace('/~~(.*?)~~/', '<del class="line-through">$1</del>', $text);
        $text = preg_replace('/`([^`]+)`/', '<code class="bg-gray-200 px-1 py-0.5 rounded">$1</code>', $text);

        /* Gestion des listes à puces */
        $text = preg_replace('/(?:^|\n)- (.+)/', "\n<li class='ml-5 list-disc'>$1</li>", $text);
        $text = preg_replace('/(<li[^>]*>.*<\/li>)/s', "<ul class='list-outside'>$1</ul>", $text);

        /* Gestion des listes numérotées */
        $text = preg_replace('/(?:^|\n)\d+\.\s(.+)/', "\n<li class='ml-5 list-decimal'>$1</li>", $text);
        $text = preg_replace('/(<li[^>]*>.*<\/li>)/s', "<ol class='list-outside'>$1</ol>", $text);

        /* Gestion des liens */
        $text = preg_replace('/\[([^\]]+)\]\((https?:\/\/[^\s]+)\)/', '<a href="$2" class="text-blue-600 hover:text-blue-800 underline" target="_blank">$1</a>', $text);

        /* Gestion des séparateurs */
        $text = preg_replace('/^\s*---+\s*$/m', '<hr class="border-t border-gray-300 my-6" />', $text);

        /* Double saut de ligne = paragraphe */
        $paragraphs = preg_split('/\n\s*\n/', trim($text));
        $paragraphs = array_map(fn($p) => "<p class='leading-relaxed mb-4'>" . nl2br($p) . "</p>", $paragraphs);

        return implode(PHP_EOL, $paragraphs);
    }
}
