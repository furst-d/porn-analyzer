<?php
declare(strict_types=1);

namespace Furstd\PornAnalyzer\Score;

class PornScoreAnalyzerResult
{
    /**
     * @var int $score
     */
    private int $score;

    /**
     * @var int $eroticBoundaryScore
     */
    private int $eroticBoundaryScore;

    /**
     * @param int $score
     * @param int $eroticBoundaryScore
     */
    public function __construct(int $score, int $eroticBoundaryScore)
    {
        $this->score = $score;
        $this->eroticBoundaryScore = $eroticBoundaryScore;
    }

    public function isErotic(): bool
    {
        return $this->score >= $this->eroticBoundaryScore;
    }

    /**
     * @return int
     */
    public function getScore(): int
    {
        return $this->score;
    }
}