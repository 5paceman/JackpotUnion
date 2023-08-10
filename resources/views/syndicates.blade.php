<x-layout.island>
    <h1 class="title">Syndicates</h1>
    <div class="padding-8 border-2">
        <h2 class="title-small">Create Syndicate</h2>
        @foreach ($errors->all() as $error)
            <p class="error">{{ $error }}</p>
        @endforeach
        <form action="/syndicate/create" method="POST">
            <label>Syndicate Name:</label>
            @csrf
            <input type="text" name="name"/ >
            <input class="button" type="submit" value="Create" />
        </form>
    </div>
    @foreach($syndicates as $syndicate)
        <a class="block-link" href="/syndicate?id={{$syndicate->id}}">
            <div class="padding-8 border-2">
                <h1><b>{{ $syndicate->name }} 
                @if($syndicate->isOwner())
                <i class="fa-solid fa-crown"></i>
                @endif
                </b></h1>
                <span class="text-small">Members: {{ $syndicate->members()->count() }}</span>
            </div>
        </a>
    @endforeach
</x-layout.island>