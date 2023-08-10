<x-layout.island>
    <div class="padding-8 grid grid-column-3 grid-row-4 gap-8">
        <h2 class="title grid-fill-row">Profile</h2>
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
        <div class="border-2 padding-8 flex-center gap-8">
            <h2 class="title-small">Change Password</h2>
            <form action="/update-password" method="POST" class="flex-center gap-8" autocomplete="off">
                @csrf
                <label>Current Password:</label>
                <input type="password" name="currentPassword" autocomplete="new-password"/>
                <label>New Password:</label>
                <input type="password" name="password" autocomplete="new-password"/>
                <label>Confirm Password:</label>
                <input type="password" name="password_confirmation" autocomplete="new-password"/>
                <input type="submit" value="Change" class="button" />
            </form>
        </div>
        <div class="border-2 padding-8 flex-center gap-8">
            <h2 class="title-small">Change Email</h2>
            <form action="/user/update" method="POST" class="flex-column gap-8 width-70p" autocomplete="off">
                @csrf
                <label>Email:</label>
                <input type="text" name="email" autocomplete="new-password"/>
                <input type="submit" value="Change" class="button" />
            </form>
        </div>
        <x-notification-box />
    </div>
</x-layout.island>