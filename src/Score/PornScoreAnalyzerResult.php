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
     * @var int $limit
     */
    private int $limit;

    /**
     * @param int $score
     * @param int $limit
     */
    public function __construct(int $score, int $limit)
    {
        $this->score = $score;
        $this->limit = $limit;
    }

    public function isErotic(): bool
    {
        return $this->score >= $this->limit;
    }

    /**
     * @return int
     */
    public function getScore(): int
    {
        return $this->score;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }
}