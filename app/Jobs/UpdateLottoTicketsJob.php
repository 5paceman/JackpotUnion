<?php

namespace App\Jobs;

use App\Mail\DrawnMailable;
use App\Mail\WinMailable;
use App\Notifications\DrawnNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\LotteryResult;
use App\Models\LotteryTicket;
use App\Notifications\WinNotification;
use Illuminate\Support\Facades\Mail;



class UpdateLottoTicketsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public LotteryResult $result;

    /**
     * Create a new job instance.
     */
    public function __construct(LotteryResult $result)
    {
        $this->result = $result;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $lottoTickets = LotteryTicket::where([
                'draw_date' => $this->result->draw_date,
                'drawn' => false
            ])->get();

        if($lottoTickets->count() == 0)
        {
            return;
        }

        $alertedSyndicates = array();

        foreach($lottoTickets as $ticket)
        {
            $matchedBalls = $this->result->matchedBalls($ticket->allBalls());
            $matchedBonus = $this->result->matchedLuckyDips($ticket->luckyDips());

            $ticket->matched_balls = $matchedBalls;
            $ticket->matched_lucky_dip = $matchedBonus;
            $ticket->won = $matchedBalls >= 2;
            $ticket->drawn = true;
            $ticket->save();

            if(!in_array($ticket->syndicate_id, $alertedSyndicates))
            {
                array_push($alertedSyndicates, $ticket->syndicate_id);
                if($ticket->syndicate->settings->email_on_drawn)
                {
                    foreach($ticket->syndicate->notificationUsers() as $user)
                    {
                        $user->notify(new DrawnNotification());
                    }
                }

                if($ticket->won && $ticket->syndicate->settings->email_on_win)
                {
                    foreach($ticket->syndicate->notificationUsers() as $user)
                    {
                        $user->notify(new WinNotification());
                    }
                }
            }
        }
    }
}
