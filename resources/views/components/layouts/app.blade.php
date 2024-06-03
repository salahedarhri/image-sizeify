<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <title>{{ $title ?? 'Image Sizeify' }}</title>
        @vite(['resources/css/app.css','resources/js/app.js'])

    </head>
    <body class="bg-gradient-to-r from-darkShade via-bgShade to-darkShade noise">
  
        <main class="my-20 antialiased font-lato">
            {{ $slot }}
        </main>
    </body>
</html>
