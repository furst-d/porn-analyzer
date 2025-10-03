<?php
declare(strict_types=1);

namespace Furstd\PornAnalyzer\Tests\Score;

use Furstd\PornAnalyzer\Score\PornScoreAnalyzer;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class PornScoreAnalyzerTest extends TestCase
{
    private function wrapScores(array $scores): array
    {
        $data = [];
        $t = 1;
        foreach ($scores as $s) {
            $data[] = ['time' => $t++, 'score' => $s];
        }
        return $data;
    }

    public function testAnalyzeSelectsTopPercentAndNormalizes(): void
    {
        // Scores 0.1 ... 1.0
        $scores = $this->wrapScores([0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9, 1.0]);
        $analyzer = new PornScoreAnalyzer(10, 50, 100);
        $result = $analyzer->analyze($scores);

        // With 10 items and top 10%, we select 1 highest score -> 1.0 -> normalized to 100
        $this->assertSame(100, $result->getScore());
        $this->assertTrue($result->isErotic());
    }

    public function testAnalyzeUsesAllWhenLessThanX(): void
    {
        // 5 items, top 10% would be 0.5 of an item, but the rule says: if less than x, use all
        $scores = $this->wrapScores([0.2, 0.4, 0.6, 0.8, 1.0]);
        $analyzer = new PornScoreAnalyzer(10, 60, 100);
        $result = $analyzer->analyze($scores);

        // average = (0.2+0.4+0.6+0.8+1.0)/5 = 0.6 -> normalized 60
        $this->assertSame(60, $result->getScore());
        $this->assertTrue($result->isErotic());
    }

    public function testAnalyzeWithProvidedSampleArray(): void
    {
        $scoresData = [
            ["time"=>1,"score"=>"0.0059"],["time"=>2,"score"=>"0.0080"],["time"=>3,"score"=>"0.0038"],
            ["time"=>4,"score"=>"0.0156"],["time"=>5,"score"=>"0.0011"],["time"=>6,"score"=>"0.0003"],
            ["time"=>7,"score"=>"0.0011"],["time"=>8,"score"=>"0.0114"],["time"=>9,"score"=>"0.0012"],
        ];
        $analyzer = new PornScoreAnalyzer(25, 220, 300);
        $result = $analyzer->analyze($scoresData);

        // Average of top 25% (2 highest): (0.0156 + 0.0114) / 2 = 0.0135 -> normalized by multiply 300 = 4.05 -> 4
        $this->assertSame(4, $result->getScore());
        $this->assertFalse($result->isErotic());
    }

    public function testInvalidScoresTopPercent(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new PornScoreAnalyzer(0, 50);
    }

    public function testInvalidScoreNormalizationMax(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new PornScoreAnalyzer(10, 5, 0);
    }

    public function testInvalidEroticBoundaryScore(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new PornScoreAnalyzer(10, 0, 100);
    }
}
