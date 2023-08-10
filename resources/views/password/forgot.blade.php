<x-layout.sidebar>
    <h1 class="title margin-bottom-12">Forgot Password</h1>
    @foreach ($errors->all() as $error)
        <p class="error">{{ $error }}</p>
    @endforeach
    <form action="/forgot-password" method="POST" class="flex-column gap-6">
        @csrf
        <label>Email:</label>
        <input type="text" name="email" />
        <input type="submit" value="Send Link" />
        <span>Need an account? <a href="/register">Register</a></span>
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </form>
</x-layout.sidebar>