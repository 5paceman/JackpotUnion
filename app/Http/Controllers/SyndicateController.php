<?php

namespace App\Http\Controllers;

use App\Mail\InviteNewUserMailable;
use App\Mail\InviteUser;
use App\Models\Invite;
use App\Notifications\InviteNotification;
use App\Notifications\JoinNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Syndicate;
use App\Models\User;
use App\Models\LotteryTicket;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class SyndicateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function all(Request $request): View
    {
        $user = User::find(auth()->user()->id);
        $syndicates = $user->syndicates;
        return view('syndicates', [
            'syndicates' => $syndicates
        ]);
    }

    public function edit(Request $request): RedirectResponse
    {
        if($request->has('rules')) {
            $validate = $request->validate([
                'rules' => 'string',
                'syndicate_id' => 'integer|exists:syndicates,id'
            ]);

            $syndicate = Syndicate::find($validate['syndicate_id']);
            $settings = $syndicate->settings;
            $settings->rules = $validate['rules'];
            $settings->save();

            return back();
        }

        $validate = $request->validate([
            'syndicate_id' => 'integer|exists:syndicates,id'
        ]);

        $syndicate = Syndicate::find($validate['syndicate_id']);
        $settings = $syndicate->settings;
        $settings->email_on_win = $request->has('emailOnWin');
        $settings->email_on_drawn = $request->has('emailOnDrawn');
        $settings->save();

        return back();
    }

    public function view(Request $request): View
    {
        $validate = $request->validate([
            'id' => 'required|integer|exists:syndicates'
        ]);

        $syndicate = Syndicate::find($validate['id']);

        $user = User::find(auth()->user()->id);

        $qb = LotteryTicket::where('drawn', true)->where('syndicate_id', $syndicate->id);
        
        if($request->has('drawDate') && !empty($request->get('drawDate')))
        {
            $qb = $qb->whereDate('draw_date', date_create($request->get('drawDate')));
        }

        if($request->has('addedDate') && !empty($request->get('addedDate')))
        {
            $qb = $qb->whereDate('created_at', date_create($request->get('addedDate')));
        }

        if($request->has('won') && !empty($request->get('won')))
        {
            $qb = $qb->where('won', true);
        }

        if($request->has('matchedBalls') && !empty($request->get('matchedBalls')))
        {
            $qb = $qb->where('matched_balls', $request->get('matchedBalls'));
        }

        if($request->has('matchedBonus') && !empty($request->get('matchedBonus')))
        {
            $qb = $qb->where('matched_lucky_dip', $request->get('matchedBonus'));
        }


        $drawnTickets = $qb->orderByDesc('created_at')->paginate($request->get('count') ?? 15)->withQueryString();

        return view('syndicate', [
            'syndicate' => $syndicate,
            'editable' => $syndicate->canEdit(auth()->user()->id),
            'drawnTickets' => $drawnTickets
        ]);
    }


    public function viewInvite(Request $request)
    {
        $validate = $request->validate([
            'code' => 'required|string|exists:invites,invite_code'
        ]);

        $invite = Invite::where('invite_code', $validate['code'])->get()->first();
        if(strcasecmp($invite->email, auth()->user()->email) != 0)
        {
            return redirect('/');
        }

        return view('invite', [
            'invite' => $invite
        ]);
    }

    public function acceptInvite(Request $request)
    {
        $validate = $request->validate([
            'accept' => 'accepted',
            'invite_code' => 'required|string|exists:invites,invite_code'
        ]);

        $invite = Invite::where('invite_code', $validate['invite_code'])->get()->first();
        
        if(strcasecmp($invite->email, auth()->user()->email) != 0)
        {
            return redirect('/');
        }

        if($invite->syndicate->members()->save(auth()->user()))
        {
            $invite->invitedBy->notify(new JoinNotification(auth()->user()->first_name, $invite->syndicate->name));
            $invite->delete();
            return redirect("/syndicate?id={$invite->syndicate->id}");
        }

        return redirect('/');
    }

    public function create(Request $request): RedirectResponse
    {
        $validate = $request->validate([
            'name' => 'string|required'
        ]);

        $syndicate = Syndicate::create([
            'name' => $validate['name'],
            'owner_id' => auth()->user()->id
        ]);

        if(!$syndicate)
        {
            return back()->withErrors(['Unable to create Syndicate.']);
        }

        $syndicate->members()->withTimestamps()->save(auth()->user());

        return redirect('/syndicates');
    }

    public function delete(Request $request): RedirectResponse
    {
        $validate = $request->validate([
            'syndicate_id' => 'required|integer|exists:syndicates,id'
        ]);

        $syndicate = Syndicate::find($validate['syndicate_id']);
        $syndicate->members()->sync([]);
        $syndicate->delete();

        return redirect('/syndicates');
    }

    public function invite(Request $request): RedirectResponse
    {
        $validate = $request->validate([
            'syndicate_id' => 'required|integer|exists:syndicates,id',
            'email' => 'required|email'
        ]);

        $user = User::where('email', $validate['email'])->get()->first();

        $invite = Invite::create([
            'invite_code' => Invite::generateInviteCode(),
            'syndicate_id' => $validate['syndicate_id'],
            'invited_by' => auth()->user()->id,
            'email' => $validate['email']
        ]);

        if(!$user)
        {
            Mail::to($validate['email'])->send(new InviteNewUserMailable($invite, !$user));
        } else {
            $user->notify(new InviteNotification($invite, false));
        }

        return back();
    }

    public function removeUser(Request $request): RedirectResponse
    {
        $validate = $request->validate([
            'syndicate_id' => 'required|integer|exists:syndicates,id',
            'user_id' => 'required|integer|exists:users,id'
        ]);

        $syndicate = Syndicate::find($validate['syndicate_id']);
        if($syndicate->owner_id == $validate['user_id'])
        {
            return back()->withErrors("You can't remove yourself if you own the syndicate.");
        }
        $syndicate->members()->detach($validate['user_id']);
        return back();
    }
}
