<?php

namespace App\Services;

use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class LottoResult {
    public $drawDate;
    public $ballOne, $ballTwo, $ballThree, $ballFour, $ballFive, $ball_lp_one, $ball_lp_two, $millionaireMaker;
    public $drawNumber;
}

class LotteryResultRetriever {
    public array $results = array();
    public string $lottoAPI;

    function __construct(string $lottoURL) {
        $this->lottoAPI = $lottoURL;
        $this->update();
    }

    public function update() : bool {
        $response = Http::get($this->lottoAPI);
        if(!$response->successful())
        {
            Log::error("Lottery results size less than or equal to 0. Check API URL. Error: {$response->body()}");
            return false;
        }


        $resultsContents = $response->body();
        $csvRows = preg_split("/\r\n|\n|\r/", $resultsContents);
        for($i = 1; $i < count($csvRows); $i++)
        {
            $row = explode(',', $csvRows[$i]);
            $result = new LottoResult();
            $result->drawDate = $row[0];
            $result->ballOne  = $row[1];
            $result->ballTwo  = $row[2];
            $result->ballThree  = $row[3];
            $result->ballFour  = $row[4];
            $result->ballFive  = $row[5];
            $result->ball_lp_one  = $row[6];
            $result->ball_lp_two  = $row[7];
            $result->millionaireMaker  = implode(',', array_splice($row, 8, -1));
            $result->drawNumber = $row[count($row) - 1];

            array_push($this->results, $result);
        }

        return true;
    }
}