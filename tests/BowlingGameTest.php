<?php

use PF\BowlingGame;
use PF\Exceptions\InvalidPointsException;
use PHPUnit\Framework\TestCase;

class BowlingGameTest extends TestCase
{
    public function testGetScore_withAllZeros_returnsZeroScore()
    {
        // setup
        $game = new BowlingGame();
        for ($i = 0; $i < 20; $i++) {
            $game->roll(0);
        }
        // test
        $score = $game->getScore();
        // assert
        self::assertEquals(0, $score);
    }

    public function testGetScore_withAllOnes_gets20asScore()
    {
        // setup
        $game = new BowlingGame();
        for ($i = 0; $i < 20; $i++) {
            $game->roll(1);
        }
        // test
        $score = $game->getScore();
        // assert
        self::assertEquals(20, $score);
    }

    public function testGetScore_wtihASpare_getsSpareBonus()
    {
        // setup
        $game = new BowlingGame();
        $game->roll(8);
        $game->roll(2);
        $game->roll(5);
        // 8 + 2 + 5 (bonus) + 5 + 17
        for ($i = 0; $i < 17; $i++) {
            $game->roll(1);
        }
        // test
        $score = $game->getScore();
        // assert
        self::assertEquals(37, $score);
    }

    public function testgetScore_withAStrike_getStrikeBonus()
    {
        // setup
        $game = new BowlingGame();
        $game->roll(10);
        $game->roll(3);
        $game->roll(5);
        // 10 + 3 (bonus) + 5 (bonus) + 3 + 5 + 16
        for ($i = 0; $i < 16; $i++) {
            $game->roll(1);
        }
        // test
        $score = $game->getScore();
        // assert
        self::assertEquals(42, $score);
    }

    public function testGetScore_forAPerfectGame_shouldReturn300()
    {
        // setup
        $game = new BowlingGame();
        for ($i = 0; $i < 12; $i++) {
            $game->roll(10);
        }
        // test
        $score = $game->getScore();
        // assert
        self::assertEquals(300, $score);
    }

    public function testGetScore_withInvalidPunctuation_shouldThrowException()
    {
        // setup
        $game = new BowlingGame();
        $errorMessage = 'Invalid points provided! The maximum is 10.';

        //expectations
        $this->expectException(InvalidPointsException::class);
        $this->expectExceptionMessage($errorMessage);
        $this->expectExceptionCode(1);

        // test
        $game->roll(11);
    }

    public function testGetScore_withInvalidPunctuation_shouldReturnZeroScore()
    {
        // setup
        $game = new BowlingGame();

        //expectations
        $this->expectException(InvalidPointsException::class);

        //invalid points
        $game->roll(11);

        // test
        $score = $game->getScore();

        //assert
        self::assertIsInt($score);
        self::assertEquals(0, $score);
    }

    public function testGetScore_withNegativeNumber_shouldThrowException()
    {
        // setup
        $game = new BowlingGame();
        $errorMessage = 'Invalid points provided! The minimum is 0.';

        //expectations
        $this->expectException(InvalidPointsException::class);
        $this->expectExceptionMessage($errorMessage);
        $this->expectExceptionCode(2);

        //test
        $game->roll(9);
        $game->roll(-4);
    }

    public function testGetScore_withNegativeNumber_shouldReturnZeroScore()
    {
        // setup
        $game = new BowlingGame();

        //expectations
        $this->expectException(InvalidPointsException::class);

        //test
        $game->roll(9);
        $game->roll(2);
        $game->roll(-4);

        // test
        $score = $game->getScore();

        //assert
        self::assertIsInt($score);
        self::assertEquals(0, $score);
    }
}