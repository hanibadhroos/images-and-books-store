<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>PicBook</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        {{-- Font-awesome Link --}}
        <link rel="stylesheet" href="{{ asset('css/font-awesome/css/all.css') }}">
        {{-- Bootstrap Link --}}
        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
        @vite('resources/js/app.js')

        <style>
            body{
                background-color: #CCC;
            }
        </style>
    </head>
    <body class="font-sans antialiased dark:bg-black dark:text-white/50">
        <div id="app">

        </div>
    </body>
</html>
