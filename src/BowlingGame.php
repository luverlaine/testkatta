<?php
namespace PF;
use PF\Exceptions\InvalidPointsException;

class BowlingGame
{
    const MAX_POINTS = 10;
    const MIN_POINTS = 0;

    private array $rolls = [];

    /**
     * @param int $points
     * @throws InvalidPointsException
     */
    public function roll(int $points): void
    {
        if ($points > self::MAX_POINTS) {
            $this->rolls = [];

            throw new InvalidPointsException('Invalid points provided! The maximum is 10.', 1);
        }

        if ($points < self::MIN_POINTS) {
            $this->rolls = [];

            throw new InvalidPointsException('Invalid points provided! The minimum is 0.', 2);
        }

        $this->rolls[] = $points;
    }

    public function getScore(): int
    {
        $score = 0;
        $roll = 0;
        for ($frame = 0; $frame < 10; $frame++) {
            if ($this->isStrike($roll)) {
                $score += $this->getStikePoints($roll);
                ++$roll;
                continue;
            }
            if ($this->isSpare($roll)) {
                $score += $this->getSpareBonus($roll);
            }
            $score += $this->getNormalScore($roll);
            $roll += 2;
        }
        return $score;
    }

    /**
     * @param int $roll
     * @return int
     */
    private function getNormalScore(int $roll): int
    {
        return $this->rolls[$roll] + $this->rolls[$roll + 1];
    }

    /**
     * @param int $roll
     * @return bool
     */
    private function isSpare(int $roll): bool
    {
        return $this->getNormalScore($roll) === 10;
    }

    /**
     * @param int $roll
     * @return int
     */
    private function getSpareBonus(int $roll): int
    {
        return $this->rolls[$roll + 2];
    }

    /**
     * @param int $roll
     * @return bool
     */
    private function isStrike(int $roll): bool
    {
        return $this->rolls[$roll] === 10;
    }

    /**
     * @param int $roll
     * @return int
     */
    private function getStikePoints(int $roll): int
    {
        return 10 + $this->rolls[$roll + 1] + $this->rolls[$roll + 2];
    }
}