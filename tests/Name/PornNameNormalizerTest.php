<?php
declare(strict_types=1);

namespace Furstd\PornAnalyzer\Tests\Name;

use Furstd\PornAnalyzer\Name\PornNameNormalizer;
use PHPUnit\Framework\TestCase;

final class PornNameNormalizerTest extends TestCase
{
    public function testNormalizeRemovesAccentsAndSpecialCharsAndCondensesSpaces(): void
    {
        $input = "Čerstvé—Máso!  porn#  test [xyz] (abc) foo-bar";
        $normalized = PornNameNormalizer::normalize($input);

        $this->assertSame('cerstve maso porn test xyz abc foo bar', $normalized);
    }

    public function testRemoveAccent(): void
    {
        $this->assertSame('AaEeIiOoUuYy', PornNameNormalizer::removeAccent('ÁáÉéÍíÓóÚúÝý'));
    }
}
