<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\LotteryResult;
use Tests\TestCase;

class WinningsTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $result = LotteryResult::create([
            'ball_one' => 1,
            'ball_two' => 1,
            'ball_three' => 1,
            'ball_four' => 1,
            'ball_five' => 1,
            'ball_six' => 1,
            'ball_bonus' => 1,
            'draw_date' => date_create("24-03-1998"),
            'ball_set' => 0,
            'draw_number' => 0,
            'machine' => 'Test'
        ]);

        $TwoBallWin = $result->matchedBalls(array(1, 1, 0, 0, 0, 0));
        $ThreeBallWin = $result->matchedBalls(array(1, 1, 1, 0, 0, 0));
        $FourBallWin = $result->matchedBalls(array(1, 1, 1, 1, 0, 0));
        $FiveBallWin = $result->matchedBalls(array(1, 1, 1, 1, 1, 0));
        $SixBallWin = $result->matchedBalls(array(1, 1, 1, 1, 1, 1));

        $this->assertEquals($result->calculateWinnings(0, false), "Lost");
        $this->assertEquals($result->calculateWinnings($TwoBallWin, false), "Free Play");
        $this->assertEquals($result->calculateWinnings($ThreeBallWin, false), "£30");
        $this->assertEquals($result->calculateWinnings($FourBallWin, false), "£140");
        $this->assertEquals($result->calculateWinnings($FiveBallWin, false), "£1,750");
        $this->assertEquals($result->calculateWinnings($FiveBallWin, true), "£1,000,000");
        $this->assertEquals($result->calculateWinnings($SixBallWin, false), "Jackpot");
    }
}
