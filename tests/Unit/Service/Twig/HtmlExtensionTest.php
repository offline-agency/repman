<?php

declare(strict_types=1);

namespace Buddy\Repman\Tests\Unit\Service\Twig;

use Buddy\Repman\Service\Twig\HtmlExtension;
use PHPUnit\Framework\TestCase;

final class HtmlExtensionTest extends TestCase
{
    private HtmlExtension $extension;

    protected function setUp(): void
    {
        $this->extension = new HtmlExtension();
    }

    public function testGetFilters(): void
    {
        self::assertEquals('compact_html', $this->extension->getFilters()[0]->getName());
    }

    public function testCompactHtml(): void
    {
        self::assertEquals(
            '<span class="token">abc</span><button>Show token</button>',
            $this->extension->compactHtml("\n    <span class=\"token\">abc</span>\n    <button>Show token</button>\n")
        );
    }

    public function testTextInsideTagsIsKept(): void
    {
        self::assertEquals('<b>two  words</b>', $this->extension->compactHtml(' <b>two  words</b> '));
    }
}
