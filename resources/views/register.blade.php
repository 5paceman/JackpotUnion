<x-layout.sidebar>
    <h1 class="title margin-bottom-12" >Register</h1>
    @foreach ($errors->all() as $error)
        <p class="error">{{ $error }}</p>
    @endforeach
    <form action="/register" method="POST" class="flex-column gap-6">
        @csrf
        <label>First Name</label>
        <input type="text" name="first_name" autocomplete="no"/>
        <label>Last Name</label>
        <input type="text" name="last_name" autocomplete="no"/>
        <label>Email:</label>
        <input type="text" name="email" autocomplete="no"/>
        <label>Password:</label>
        <input type="password" name="password" autocomplete="no"/>
        <label>Confirm Password:</label>
        <input type="password" name="password_confirmation" autocomplete="no"/>
        <input type="submit" value="Register" />
        <span>Already got an account? <a href="/login">Login</a></span>
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </form>
</x-layout.sidebar>