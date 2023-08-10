<x-layout.sidebar>
    <h1 class="title margin-bottom-12">Reset Password</h1>
    @foreach ($errors->all() as $error)
        <p class="error">{{ $error }}</p>
    @endforeach
    <form action="/reset-password" method="POST" class="flex-column gap-6" autocomplete="no">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}" autocomplete="no"/>
        <label>Email:</label>
        <input type="text" name="email"  autocomplete="no" />
        <label>Password:</label>
        <input type="password" name="password" autocomplete="no"/>
        <label>Confirm Password:</label>
        <input type="password" name="password_confirmation" autocomplete="no"/>
        <input type="submit" value="Reset" />
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </form>
</x-layout.sidebar>