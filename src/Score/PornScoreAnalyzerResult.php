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
     * @var array $selectedScores
     */
    private array $selectedScores;

    /**
     * @param int $score
     * @param int $eroticBoundaryScore
     * @param array $selectedScores
     */
    public function __construct(int $score, int $eroticBoundaryScore, array $selectedScores)
    {
        $this->score = $score;
        $this->eroticBoundaryScore = $eroticBoundaryScore;
        $this->selectedScores = $selectedScores;
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

    /**
     * @return array
     */
    public function getSelectedScores(): array
    {
        return $this->selectedScores;
    }

    /**
     * @return int
     */
    public function getSelectedScoreCount(): int
    {
        return count($this->selectedScores);
    }
}