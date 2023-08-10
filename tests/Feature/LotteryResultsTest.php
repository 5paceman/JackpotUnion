<?php

namespace Tests\Feature;

use App\Services\LotteryResultRetriever;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class LotteryResultsTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $results = new LotteryResultRetriever("https://www.national-lottery.co.uk/results/lotto/draw-history/csv");
        Log::info($results->formattedResults());
        $this->assertIsNumeric($results->ballOne);
        $this->assertIsNumeric($results->ballTwo);
        $this->assertIsNumeric($results->ballThree);
        $this->assertIsNumeric($results->ballFour);
        $this->assertIsNumeric($results->ballFive);
        $this->assertIsNumeric($results->ballSix);
        $this->assertIsNumeric($results->bonusBall);
        $this->assertIsNumeric($results->ballSet);
    }
        
}
