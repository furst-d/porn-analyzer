<?php
declare(strict_types=1);

namespace Furstd\PornAnalyzer\Score;

final class PornScoreAnalyzer
{
    /**
     * @var int $scoresTopPercent
     */
    private int $scoresTopPercent;

    /**
     * @param int $scoresTopPercent
     */
    public function __construct(int $scoresTopPercent)
    {
        $this->scoresTopPercent = $scoresTopPercent;
    }

    public function analyze(array $scores)
    {

    }

    /**
     * @return int
     */
    public function getScoresTopPercent(): int
    {
        return $this->scoresTopPercent;
    }

    /**
     * @param int $scoresTopPercent
     * @return void
     */
    public function setScoresTopPercent(int $scoresTopPercent): void
    {
        $this->scoresTopPercent = $scoresTopPercent;
    }
}