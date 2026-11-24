<?php

declare(strict_types=1);

namespace Buddy\Repman\Service\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Replaces the core "spaceless" filter, deprecated since Twig 3.12.
 */
final class HtmlExtension extends AbstractExtension
{
    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('compact_html', [$this, 'compactHtml'], ['is_safe' => ['html']]),
        ];
    }

    public function compactHtml(string $html): string
    {
        return trim((string) preg_replace('/>\s+</', '><', $html));
    }
}
