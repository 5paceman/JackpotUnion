<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LotteryResult extends Model
{
    protected $table = "lottery_results";
    protected $guarded = [];

    public function formattedResults() : string {
        return "{$this->ball_one}-{$this->ball_two}-{$this->ball_three}-{$this->ball_four}-{$this->ball_five} Lucky Dips: {$this->ball_lp_one}-{$this->ball_lp_two}";
    }

    public function formattedResultWithDate(): string {
        $date = date_create($this->draw_date);
        return "{$this->ball_one}-{$this->ball_two}-{$this->ball_three}-{$this->ball_four}-{$this->ball_five} Lucky Dips: {$this->ball_lp_one}-{$this->ball_lp_two} - Draw Date: {$date->format("d-m-y")}";
    }

    public function allBalls() : array
    {
        return array($this->ball_one, $this->ball_two, $this->ball_three, $this->ball_four, $this->ball_five);
    }

    public function luckyDips(): array
    {
        return array($this->ball_lp_one, $this->ball_lp_two);
    }

    public function matchedBalls(array $balls) : int
    {
        
        $difference = array_intersect($balls, $this->allBalls());
        return count($difference);
    }

    public function matchedLuckyDips(array $balls) : int
    {
        
        $difference = array_intersect($balls, $this->luckyDips());
        return count($difference);
    }

    public function calculateWinnings(int $matchedBalls, bool $matchedBonus): string
    {
        switch($matchedBalls) 
        {
            case 1:
                if($matchedBonus == 2)
                {
                    return "3.60"
                } else {
                    return "Lost";
                }
                break;
            case 2: {
                switch($matchedBonus)
                {
                    default:
                        return "£2.50";

                    case 1:
                        return "£3.60";

                    case 2:
                        return "£9.10";
                }
            }

            case 3: {
                switch($matchedBonus)
                {
                    default:
                        return "£6";

                    case 1:
                        return "£7.3";

                    case 2:
                        return "£37.30";
                }
            }

            case 4: {
                switch($matchedBonus)
                {
                    default:
                        return "£25.60";

                    case 1:
                        return "£77.80";

                    case 2:
                        return "£844.70";
                }
            }

            case 5: {
                switch($matchedBonus)
                {
                    default:
                        return "£13,561.20";

                    case 1:
                        return "£130,554.30";

                    case 2:
                        return "Jackpot";
                }
            }

            default: {
                return "Lost";
            }
        }
    }
}
