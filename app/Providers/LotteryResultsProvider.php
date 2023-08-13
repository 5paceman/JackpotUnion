<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\LotteryResults;
use Illuminate\Support\Facades\Blade;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\DB;
use App\Models\LotteryResult;

class LotteryResultsProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Blade::directive('lottoresult', function(string $expression) {
            $lottoResult = LotteryResult::all()->sortByDesc('draw_date')->first();
            return $lottoResult ? $lottoResult->formattedResults() : 'Unable to get results';
        });

        Blade::directive('drawdate', function(string $expression) {
            $lottoResult = LotteryResult::all()->sortByDesc('draw_date')->first();
            return $lottoResult ? date_create($lottoResult->draw_date)->format('d-m-Y') : 'Unable to get draw date';
        });
    }
}
