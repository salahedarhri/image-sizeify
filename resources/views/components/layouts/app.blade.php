<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('icon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('icon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('icon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('icon/site.webmanifest') }}">

    <title>{{ $title ?? 'Image Sizeify' }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])

</head>
<body class="bg-whiteShade dark:bg-darkShade antialiased">
    <nav class="bg-whiteShade dark:bg-darkShade fixed w-full z-20 top-0 start-0 border-b border-lightShade dark:border-mediumShade font-barlow">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="{{ route('home') }}" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="{{ asset('images/logo.png')}}" class="h-8 dark:invert" alt="logo">
                <span class="self-center text-xl text-mediumShade font-semibold dark:text-lightShade">ImageSizeify</span>
            </a>
            <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
                <button id="theme-toggle" type="button" class="text-gray-500 dark:text-gray-400 hover:bg-lightShade dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-lightShade  dark:focus:ring-gray-700 rounded-lg text-sm p-2.5">                <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                    <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                </button>
                <button data-collapse-toggle="navbar-sticky" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-lightShade focus:outline-none focus:ring-2 focus:ring-lightShade  dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-mediumShade" aria-controls="navbar-sticky" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
                    </svg>
                </button>
            </div>
            <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
            <ul class="flex flex-col p-4 md:p-0 mt-4 font-medium border border-lightShade rounded-lg bg-lightShade md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-transparent dark:bg-darkShade md:dark:bg-darkShade dark:border-darkShade">
                <li><a wire:navigate href="{{ route('home') }}" class="block py-2 px-3 text-darkShade rounded hover:bg-lightShade md:hover:bg-transparent md:hover:text-mediumShade md:p-0 md:dark:hover:text-lightShade  dark:text-whiteShade dark:hover:bg-mediumShade dark:hover:text-lightShade md:dark:hover:bg-transparent dark:border-mediumShade">Home</a></li>
                <li><a wire:navigate href="{{ route('api') }}" class="block py-2 px-3 text-darkShade rounded hover:bg-lightShade md:hover:bg-transparent md:hover:text-mediumShade md:p-0 md:dark:hover:text-lightShade  dark:text-whiteShade dark:hover:bg-mediumShade dark:hover:text-lightShade md:dark:hover:bg-transparent dark:border-mediumShade">API</a></li>
            </ul>
            </div>
        </div>
    </nav>
  
    <main class="flex-1">
        {{ $slot }}
    </main>

    @stack('custom-scripts')

    <footer class="bg-whiteShade rounded-t-lg shadow dark:bg-darkShade font-barlow">
        <div class="w-full mx-auto max-w-screen-xl p-4 md:flex md:items-center md:justify-between">
            <span class="text-sm max-sm:text-center text-darkShade dark:text-whiteShade">© 2024 <a href="https://github.com/salahedarhri" class="hover:underline">Salah eddin ARHRIMAZ.</a>
            </span>
            <ul class="flex flex-wrap items-center mt-3 text-sm font-medium text-darkShade dark:text-whiteShade sm:mt-0">
                <li><a wire:navigate href="{{ route('home') }}" class="hover:underline me-4 md:me-6">Home</a></li>
                <li><a wire:navigate href="{{ route('api') }}" class="hover:underline me-4 md:me-6">API</a></li>
            </ul>
        </div>
    </footer>


</body>
</html>
