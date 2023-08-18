<!DOCTYPE html>
<html class="fill" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="{{ asset('css/teamlotto.css') }}" />
        <link rel="stylesheet" href="{{ asset('css/login.css') }}" />
        <link rel="stylesheet" href="{{ asset('css/sidebar-responsive.css') }}" />

        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">
        <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">
        <title>{{ config('app.name') }}</title>
    </head>
    <body class="fill">
        <section class="loginLeft flex-center gap-12">
            <h1>Jackpot<span class="titleLotto">Union</span></h1>
            <div>
                <span><b>Last Winning Numbers:</b></span>
                <span>@lottoresult</span>
            </div>
            <div>
                <span><b>Last Draw Date:</b></span>
                <span>@drawdate</span>
            </div>
        </section>
        <section class="loginRight flex-center flex-grow gap-12">
            {{ $slot }}
        </section>
    </body>
</html>
