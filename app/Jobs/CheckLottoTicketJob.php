<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\LotteryTicket;
use App\Models\LotteryResult;
use App\Notifications\DrawnNotification;
use App\Notifications\WinNotification;

class CheckLottoTicketJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public LotteryTicket $ticket;
    public bool $sendNotification;
    /**
     * Create a new job instance.
     */
    public function __construct(LotteryTicket $ticket, bool $sendNotification = false)
    {
        $this->ticket = $ticket;
        $this->sendNotification = $sendNotification;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $drawDate = date_create($this->ticket->draw_date);
        $result = LotteryResult::where('draw_date', $drawDate)->get()->first();
        if(!$result)
        {
            return;
        }

        $matchedBalls = $result->matchedBalls($this->ticket->allBalls());
        $matchedBonus = $result->matchedLuckyDips($this->ticket->luckyDips());

        $this->ticket->matched_balls = $matchedBalls;
        $this->ticket->matched_lucky_dip = $matchedBonus;
        $this->ticket->won = $matchedBalls >= 2;
        $this->ticket->drawn = true;
        $this->ticket->save();

        if($this->sendNotification)
        {
            if($this->ticket->syndicate->settings->email_on_drawn)
            {
                foreach($this->ticket->syndicate->notificationUsers() as $user)
                {
                    $user->notify(new DrawnNotification());
                }
            }
    
            if($this->ticket->won && $this->ticket->syndicate->settings->email_on_win)
            {
                foreach($this->ticket->syndicate->notificationUsers() as $user)
                {
                    $user->notify(new WinNotification());
                }
            }
        }
    }
}
