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
        $retriever = new LotteryResultRetriever("https://www.national-lottery.co.uk/results/euromillions/draw-history/csv");

        foreach($retriever->results as $result)
        {
            $this->assertIsNumeric($result->ballOne);
            $this->assertIsNumeric($result->ballTwo);
            $this->assertIsNumeric($result->ballThree);
            $this->assertIsNumeric($result->ballFour);
            $this->assertIsNumeric($result->ballFive);
            $this->assertIsNumeric($result->ball_lp_one);
            $this->assertIsNumeric($result->ball_lp_two);

            $this->assertIsNumeric($result->drawNumber);
            $this->assertIsObject(date_create($result->drawDate));
        }
    }
        
}
