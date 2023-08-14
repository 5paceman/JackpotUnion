<x-layout.island>
    <div class="padding-8 grid grid-column-3 grid-row-4 gap-8">
        <h2 class="title grid-fill-row">{{ $syndicate->name }}</h2>
        @foreach ($errors->all() as $error)
            <p class="error grid-fill-row">{{ $error }}</p>
        @endforeach
        <div class="border-2 padding-8">
            <h2 class="title-small margin-bottom-8">Members</h2>
            <div class="scroll height-6h">
                <ul class="list">
                    @foreach($syndicate->members as $user)
                        <li> {{ $editable ? $user->email : $user->name() }} @if($editable)<form class="margin-left-auto" action="/syndicate/user/remove" method="post">@csrf<input type="hidden" name="syndicate_id" value="{{$syndicate->id}}"/><input type="hidden" name="user_id" value="{{$user->id}}"/><input type="submit" class="button" value="Delete"></form>@endif</li>
                    @endforeach
                    @if($editable)
                    @foreach($syndicate->invites as $invite)
                        <li> {{ $invite->email }} - Invite Sent </li>
                    @endforeach
                    @else
                        <li> Pending Invites: {{ $syndicate->invites->count() }} </li>
                    @endif
                </ul>
            </div>
            @if($editable)
            <form action="/syndicate/invite" method="POST">
                <label>Invite Member: </label>
                <input type="hidden" name="syndicate_id" value="{{$syndicate->id}}" />@csrf
                <input type="text" name="email" placeholder="hello@google.com"/>
                <input type="submit" value="Invite" class="button margin-left-auto" />
            </form>
            @endif
        </div>
        <div class="border-2 padding-8">
            <h2 class="title-small margin-bottom-8">Undrawn Tickets</h2>
            <div class="scroll height-6h">
                <table class="ticket-table">
                    <thead>
                        <tr style="background-color: #3460f23b; font-weight: bold;">
                            <th scope="col">1</th>
                            <th scope="col">2</th>
                            <th scope="col">3</th>
                            <th scope="col">4</th>
                            <th scope="col">5</th>
                            <th scope="col">Lucky Dip 1</th>
                            <th scope="col">Lucky Dip 2</th>
                            <th scope="col">Draw Date</th>
                            @if($editable)
                            <th scope="col"><i class="fa-solid fa-trash-can"></i></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($syndicate->undrawnTickets as $ticket)
                        <tr>
                            <td>{{ $ticket->ball_one }}</td>
                            <td>{{ $ticket->ball_two }}</td>
                            <td>{{ $ticket->ball_three }}</td>
                            <td>{{ $ticket->ball_four }}</td>
                            <td>{{ $ticket->ball_five }}</td>
                            <td>{{ $ticket->ball_lp_one }}</td>
                            <td>{{ $ticket->ball_lp_two }}</td>
                            <td>{{ date_create($ticket->draw_date)->format("d-m-Y") }}</td>
                            @if($editable)
                            <td>
                                <form action="/ticket/delete" method="post">
                                    @csrf
                                    <input type="hidden" name="ticket_id" value="{{$ticket->id}}"/>
                                    <input type="submit" class="button" value="Delete" />
                                </form>
                            </td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @if($editable)
            <div class="padding-8 border-2">
                <h2 class="title-small margin-bottom-8">Add Ticket</h2>
                <form action="/ticket/create" method="POST" class="grid grid-column-4 grid-row-4 gap-8">
                    @csrf
                    <input type="hidden" name="syndicate_id" value="{{$syndicate->id}}" />
                    <label>Ball One:</label>
                    <input type="number" name="ballOne" />
                    <label>Ball Two:</label>
                    <input type="number" name="ballTwo" />
                    <label>Ball Three:</label>
                    <input type="number" name="ballThree" />
                    <label>Ball Four:</label>
                    <input type="number" name="ballFour" />
                    <label>Ball Five:</label>
                    <input type="number" name="ballFive" />
                    <label>Lucky Dip 1:</label>
                    <input type="number" name="ballSix" />
                    <label>Lucky Dip 2:</label>
                    <input type="number" name="bonusBall" />
                    <label>Draw Date:</label>
                    <input type="date" name="drawDate" />
                    <input type="submit" value="Add" class="button" />
                </form>
            </div>
        @else
            <div class="border-2 padding-8">
                <h2 class="title-small margin-bottom-8">Syndicate Settings</h2>
                <div class="flex-center gap-12" style="min-width: 0">
                    <h2>Rules:</h2>
                    <textarea readonly class="textarea width-80p height-160px" type="text" name="name">{{ $syndicate->settings->rules }}</textarea>
                </div>
            </div>
        @endif
        <div class="border-2 padding-8 {{ $editable ? 'grid-fill-row-2' : 'grid-fill-row'}}">
            <h2 class="title-small margin-bottom-8">Drawn Tickets</h2>
            <table class="ticket-table margin-bottom-8">
                <thead>
                    <tr>
                        <form id="drawnTicketsForm" action="/syndicate" method="GET">
                            <input type="hidden" value="{{$syndicate->id}}" name="id" />
                        <th colspan="7"></th>
                        <th><input form="drawnTicketsForm" type="date" name="drawDate" value="{{ request()->get('drawDate')}}"/></th>
                        <th><input form="drawnTicketsForm" type="date" name="addedDate" value="{{ request()->get('addedDate')}}"/></th>
                        <th><input form="drawnTicketsForm" type="checkbox" name="won" value="yes" {{ request()->get('won') == 'yes' ? "checked" : ""}}/></th>
                        <th><input form="drawnTicketsForm" type="number" name="matchedBalls" value="{{ request()->get('matchedBalls')}}"/></th>
                        <th><input form="drawnTicketsForm" type="number" name="matchedBonus" value="{{ request()->get('matchedBonus')}}"/></th>
                        <th><input class="button" type="submit" value="Filter" form="drawnTicketsForm"/></th>
                        </form>
                    </tr>
                    <tr>
                        <th>1</th>
                        <th>2</th>
                        <th>3</th>
                        <th>4</th>
                        <th>5</th>
                        <th>Lucky Dip 1</th>
                        <th>Lucky Dip 2</th>
                        <th>Draw Date</th>
                        <th>Added</th>
                        <th>Won</th>
                        <th>Matched Balls</th>
                        <th>Matched Bonus</th>
                        <th>Winnings</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($drawnTickets as $ticket)
                        <tr>
                            <td>{{ $ticket->ball_one }}</td>
                            <td>{{ $ticket->ball_two }}</td>
                            <td>{{ $ticket->ball_three }}</td>
                            <td>{{ $ticket->ball_four }}</td>
                            <td>{{ $ticket->ball_five }}</td>
                            <td>{{ $ticket->ball_lp_one }}</td>
                            <td>{{ $ticket->ball_lp_two }}</td>
                            <td>{{ date_create($ticket->draw_date)->format("d-m-Y") }}</td>
                            <td>{{ date_create($ticket->created_at)->format("d-m-Y") }}</td>
                            <td>{{ $ticket->won ? "Y" : "X" }}</td>
                            <td>{{ $ticket->matched_balls }}</td>
                            <td>{{ $ticket->matched_lucky_dip }}</td>
                            <td>{{ $ticket->calculateWinnings() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="flex-row gap-12 center-x">
                <div>
                    <label class="title-small">Items:</label>
                    <select form="drawnTicketsForm" name="count" autocomplete="off">
                        <option {{ request()->get('count') == 15 ? 'selected=selected' : ''}} value="15">15</option>
                        <option {{ request()->get('count') == 30 ? 'selected=selected' : ''}} value="30">30</option>
                        <option {{ request()->get('count') == 50 ? 'selected=selected' : ''}} value="50">50</option>
                        <option {{ request()->get('count') == 100 ? 'selected=selected' : ''}} value="100">100</option>
                        <option {{ request()->get('count') == 200 ? 'selected=selected' : ''}} value="200">200</option>
                    </select>
                </div>
                <div style="margin-left: auto; text-align: right;">
                    {{ $drawnTickets->links('components.partials.pagination') }}
                </div>
            </div>
        </div>
        @if($editable)
            <div class="border-2 padding-8">
                <h2 class="title-small margin-bottom-8">Syndicate Settings</h2>
                <div class="flex-row center gap-12">
                    <form action="/syndicate/edit" method="POST" class="flex-center gap-8 border-2-dotted padding-8">
                        <label>Rules:</label>
                        @csrf
                        <textarea class="textarea" type="text" name="rules">{{ $syndicate->settings->rules }}</textarea>
                        <input type="hidden" name="syndicate_id" value="{{$syndicate->id }}"/>
                        <input type="submit" value="Save" class="button" />
                    </form>
                    <form action="/syndicate/edit" method="POST" class="flex-center gap-8 border-2-dotted padding-8">
                        <h2 class="title-small margin-bottom-8">Email Options</h2>
                        <label>Email on Win:</label>
                        @csrf
                        <input type="checkbox" name="emailOnWin" {{ $syndicate->settings->email_on_win ? 'checked' : '' }}/>
                        <label>Email on Drawn:</label>
                        <input type="checkbox" name="emailOnDrawn" {{ $syndicate->settings->email_on_drawn ? 'checked' : ''}} />
                        <input type="hidden" name="syndicate_id" value="{{$syndicate->id }}"/>
                        <input type="submit" value="Save" class="button" />
                    </form>
                </div>
            </div>
        @endif
    </div>
</x-layout.island>