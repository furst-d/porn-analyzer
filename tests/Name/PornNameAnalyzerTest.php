<?php
declare(strict_types=1);

namespace Furstd\PornAnalyzer\Tests\Name;

use Furstd\PornAnalyzer\Name\PornNameAnalyzer;
use PHPUnit\Framework\TestCase;

final class PornNameAnalyzerTest extends TestCase
{
    public function testPhraseHasPrecedenceOverTerm(): void
    {
        $analyzer = new PornNameAnalyzer(['porn'], ['animal porn']);
        $result = $analyzer->analyze('My video about Animal-Porn and more');
        $this->assertTrue($result->isErotic());
        $this->assertSame('animal porn', $result->getFoundMatch());
    }

    public function testTermMatchAfterNormalization(): void
    {
        $analyzer = new PornNameAnalyzer(['lesbian'], []);
        $result = $analyzer->analyze('Some LESBIÁN vibes'); // diacritics + case
        $this->assertTrue($result->isErotic());
        $this->assertSame('lesbian', $result->getFoundMatch());
    }

    public function testAddAndRemoveTermsAndPhrases(): void
    {
        $analyzer = new PornNameAnalyzer();
        $analyzer->addPornTerm('xxx');
        $analyzer->addPornPhrase('adult movie');

        // initially matches phrase
        $r1 = $analyzer->analyze('Top ADULT-movie tonight');
        $this->assertTrue($r1->isErotic());
        $this->assertSame('adult movie', $r1->getFoundMatch());

        // remove phrase and rely on term
        $analyzer->removePornPhrase('adult movie');
        $r2 = $analyzer->analyze('best XXX scenes');
        $this->assertTrue($r2->isErotic());
        $this->assertSame('xxx', $r2->getFoundMatch());

        // remove term too -> no match
        $analyzer->removePornTerm('xxx');
        $r3 = $analyzer->analyze('nothing xxx here');
        $this->assertFalse($r3->isErotic());
        $this->assertNull($r3->getFoundMatch());
    }
}
