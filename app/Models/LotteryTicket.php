<?php

namespace App\Models;

use App\Jobs\CheckLottoTicketJob;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class LotteryTicket extends Model
{
    protected $guarded = [];
    protected $table = "lottery_tickets";

    protected static function booted(): void
    {
        static::created(function (LotteryTicket $ticket) {
            CheckLottoTicketJob::dispatch($ticket);
        });
    }

    public function formattedResults() : string {
        return "{$this->ball_one}-{$this->ball_two}-{$this->ball_three}-{$this->ball_four}-{$this->ball_five} Lucky Dips: {$this->ball_lp_one} {$this->ball_lp_two}";
    }

    public function formattedResultWithDate(): string {
        $date = date_create($this->draw_date);
        return "{$this->ball_one}-{$this->ball_two}-{$this->ball_three}-{$this->ball_four}-{$this->ball_five} Lucky Dips: {$this->ball_lp_one} {$this->ball_lp_two} - Draw Date: {$date->format("d-m-y")}";
    }

    public function allBalls() : array
    {
        return array($this->ball_one, $this->ball_two, $this->ball_three, $this->ball_four, $this->ball_five);
    }

    public function luckyDips(): array
    {
        return array($this->ball_lp_one, $this->ball_lp_two);
    }

    public function syndicate(): BelongsTo
    {
        return $this->belongsTo(Syndicate::class);
    }
    
    public function calculateWinnings(): string
    {
        switch($this->matched_balls) 
        {
            case 1:
                if($this->matched_lucky_dip == 2)
                {
                    return "£3.60";
                } else {
                    return "Lost";
                }

            case 2: {
                switch($this->matched_lucky_dip)
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
                switch($this->matched_lucky_dip)
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
                switch($this->matched_lucky_dip)
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
                switch($this->matched_lucky_dip)
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
