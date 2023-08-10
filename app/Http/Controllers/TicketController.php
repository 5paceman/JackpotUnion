<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\LotteryTicket;
use App\Models\Syndicate;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Request $request): RedirectResponse
    {
        $validate = $request->validate([
            'syndicate_id' => 'required|integer|exists:syndicates,id',
            'drawDate' => 'required|date',
            'ballOne' => 'required|integer',
            'ballTwo' => 'required|integer',
            'ballThree' => 'required|integer',
            'ballFour' => 'required|integer',
            'ballFive' => 'required|integer',
            'ballSix' => 'required|integer',
            'bonusBall' => 'required|integer',
        ]);

        $syndicate = Syndicate::find($validate['syndicate_id']);
        if($syndicate->owner_id != auth()->user()->id)
        {
            return back()->withErrors(['You dont have permission to add tickets.']);
        }

        $ticket = LotteryTicket::create([
            'added_by' => auth()->user()->id,
            'syndicate_id' => $validate['syndicate_id'],
            'draw_date' => $validate['drawDate'],
            'ball_one' => $validate['ballOne'],
            'ball_two' => $validate['ballTwo'],
            'ball_three' => $validate['ballThree'],
            'ball_four' => $validate['ballFour'],
            'ball_five' => $validate['ballFive'],
            'ball_lp_one' => $validate['ballSix'],
            'ball_lp_two' => $validate['bonusBall']
        ]);

        if(!$ticket)
        {
            return back()->withErrors('Unable to create ticket');
        }
        return back();
    }

    public function delete(Request $request): RedirectResponse
    {
        $validate = $request->validate([
            'ticket_id' => 'required|integer|exists:lottery_tickets,id'
        ]);

        $ticket = LotteryTicket::find($validate['ticket_id']);
        if($ticket->added_by != auth()->user()->id)
        {
            return back()->withErrors(['You dont have permission to delete this ticket.']);
        }
        $ticket->delete();

        return back();
    }
}
