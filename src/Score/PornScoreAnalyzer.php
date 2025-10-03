<?php
declare(strict_types=1);

namespace Furstd\PornAnalyzer\Score;

use InvalidArgumentException;

final class PornScoreAnalyzer
{
    /**
     * @var int $scoresTopPercent
     */
    private int $scoresTopPercent;

    /**
     * @var int $eroticBoundaryScore
     */
    private int $eroticBoundaryScore;

    /**
     * @var int $scoreNormalizationMax
     */
    private int $scoreNormalizationMax;

    /**
     * @param int $scoresTopPercent
     * @param int $eroticBoundaryScore
     * @param int $scoreNormalizationMax
     */
    public function __construct(int $scoresTopPercent, int $eroticBoundaryScore, int $scoreNormalizationMax = 100)
    {
        $this->setScoresTopPercent($scoresTopPercent);
        $this->setScoreNormalizationMax($scoreNormalizationMax);
        $this->setEroticBoundaryScore($eroticBoundaryScore);
    }

    /**
     * Analyze the scores and return the decision
     *
     * @param array $scoresData
     * @return PornScoreAnalyzerResult
     */
    public function analyze(array $scoresData): PornScoreAnalyzerResult
    {
        $x = 100 / $this->scoresTopPercent;
        $selectedCount = count($scoresData) >= $x
            ? (int) (count($scoresData) / $x) // Highest 1/x of checks
            : count($scoresData); // If there are less than x checks, use all of them

        $score = $this->computeScore($scoresData, $selectedCount);

        return new PornScoreAnalyzerResult($score, $this->eroticBoundaryScore);
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
        if ($scoresTopPercent < 1 || $scoresTopPercent > 100) {
            throw new InvalidArgumentException('ScoresTopPercent must be between 1 and 100.');
        }

        $this->scoresTopPercent = $scoresTopPercent;
    }

    /**
     * @return int
     */
    public function getEroticBoundaryScore(): int
    {
        return $this->eroticBoundaryScore;
    }

    /**
     * @param int $eroticBoundaryScore
     * @return void
     */
    public function setEroticBoundaryScore(int $eroticBoundaryScore): void
    {
        if ($eroticBoundaryScore < 1 || $eroticBoundaryScore > $this->scoreNormalizationMax) {
            throw new InvalidArgumentException('EroticBoundaryScore must be between 1 and scoreNormalizationMax.');
        }

        $this->eroticBoundaryScore = $eroticBoundaryScore;
    }

    /**
     * @return int
     */
    public function getScoreNormalizationMax(): int
    {
        return $this->scoreNormalizationMax;
    }

    /**
     * @param int $scoreNormalizationMax
     * @return void
     */
    public function setScoreNormalizationMax(int $scoreNormalizationMax): void
    {
        if ($scoreNormalizationMax < 1) {
            throw new InvalidArgumentException('ScoreNormalizationMax must be greater than 0.');
        }

        $this->scoreNormalizationMax = $scoreNormalizationMax;
    }

    /**
     * Compute the score based on the average of the top selected scores
     *
     * @param array $scoresData
     * @param int $selectedCount
     * @return int
     */
    private function computeScore(array $scoresData, int $selectedCount): int
    {
        $topScores = $this->selectTopScores($scoresData, $selectedCount);

        $scores = array_map(function ($scoreData) {
            return $scoreData['score'];
        }, $topScores); // Extract scores from the top scores data

        return $this->normalizeScore(array_sum($scores) / count($scores));
    }

    /**
     * Select the top x scores from the score array in descending order
     *
     * @param array $scoresData
     * @param int $selectedCount
     * @return array
     */
    private function selectTopScores(array $scoresData, int $selectedCount): array
    {
        // Sort the scores in descending order
        usort($scoresData, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        // Get the highest x scores
        return array_slice(array_values($scoresData), 0, $selectedCount);
    }

    /**
     * Normalize the score to a value between 0 and scoreNormalizationMax
     *
     * @param float $score
     * @return int
     */
    private function normalizeScore(float $score): int
    {
        // The score should not be negative or greater than 1, but just in case we clamp it
        $score = max(0, min(1, $score));

        return (int) ($score * $this->scoreNormalizationMax);
    }
}