<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\LotteryResultRetriever;
use App\Models\LotteryResult;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UpdateLottoResultJob implements ShouldQueue
{
    // app(\Illuminate\Contracts\Bus\Dispatcher::class)->dispatch(new App\Jobs\UpdateLottoResultJob(env('EUROMILLIONS_API')));
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private string $lottoAPIURL;
    /**
     * Create a new job instance.
     */
    public function __construct(string $lottoAPIURL)
    {
        $this->lottoAPIURL = $lottoAPIURL;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $retriever = new LotteryResultRetriever($this->lottoAPIURL);

        foreach($retriever->results as $result)
        {
            $drawDate = date_create($result->drawDate);
            if(!LotteryResult::where('draw_date', $drawDate)->exists())
            {
                $newResult = LotteryResult::create([
                    'draw_date' => $drawDate,
                    'ball_one' => $result->ballOne,
                    'ball_two' => $result->ballTwo,
                    'ball_three' => $result->ballThree,
                    'ball_four' => $result->ballFour,
                    'ball_five' => $result->ballFive,
                    'ball_lp_one' => $result->ball_lp_one,
                    'millionaire_maker' => $result->millionaireMaker,
                    'ball_lp_two' => $result->ball_lp_two,
                    'draw_number' => $result->drawNumber
                ]);

                if(!$newResult)
                {
                    $this->fail();
                }

                UpdateLottoTicketsJob::dispatch($newResult);
            }
        }
    }
}
