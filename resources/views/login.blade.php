<x-layout.sidebar>
    <h1 class="title margin-bottom-12">Login</h1>
    @foreach ($errors->all() as $error)
        <p class="error">{{ $error }}</p>
    @endforeach
    <form action="/login" method="POST" class="flex-column gap-6">
        @csrf
        <label>Email:</label>
        <input type="text" name="email" />
        <label>Password:</label>
        <input type="password" name="password" />
        <div><input type="checkbox" id="remember" name="remember" />
        <label for="remember">Remember me?</label></div>
        <input type="submit" value="Login" />
        <span>Need an account? <a href="/register">Register</a></span>
        <span>Forgot your password? <a href="/forgot-password">Reset</a></span>
    </form>
</x-layout.sidebar>