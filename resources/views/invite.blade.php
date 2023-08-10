<x-layout.island>
    <div class="padding-8 flex-center gap-8">
        <h2 class="title-small">Syndicate Invite</h2>
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
        <div class="border-2 padding-8 flex-center gap-8 width-40p">
            <h2 class="title-small">{{ $invite->invitedBy->name() }} has invited you to {{ $invite->syndicate->name }}</h2>
            <h3>Syndicate Rules:</h3>
            <textarea readonly class="width-80p height-200px">{{ $invite->syndicate->settings->rules }}</textarea>
            <form id="acceptForm" action="/syndicate/invite/accept" method="POST" class="flex-center gap-8">
                <div>
                    <label>Accept Rules:</label>
                    @csrf
                    <input type="hidden" name="invite_code" value="{{ $invite->invite_code }}" />
                    <input form="acceptForm" type="checkbox" name="accept" />
                </div>
                <input type="submit" value="Accept" class="button" />
            </form>
        </div>
    </div>
</x-layout.island>