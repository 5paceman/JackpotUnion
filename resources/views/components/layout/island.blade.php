<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="{{ asset('css/teamlotto.css') }}" />
        <link href="https://db.onlinewebfonts.com/c/29aaa00c8f322b47863a40b19b7e9e6b?family=Effra+Heavy" rel="stylesheet"> 

        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">
        <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">
        <title>{{ config('app.name') }}</title>
    </head>
    <body class="flex-center padding-4">
        <h1 class="logo margin-bottom-12">Jackpot<span class="logo-half">Union</span></h1>
        <div id="floating-middle" class="border-4 floating-island box-shadow flex-column">
            <x-navbar />
            <main class="flex-column gap-8 padding-12">
                {{ $slot }}
            </main>
        </div>
        <script src="https://kit.fontawesome.com/99639637ba.js" crossorigin="anonymous"></script>
    </body>
</html>
