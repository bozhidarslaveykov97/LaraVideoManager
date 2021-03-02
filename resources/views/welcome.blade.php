<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>LaraVideoManager</title>

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    </head>
    <body class="antialiased">

        <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">

            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">
                    <div class="h-16 w-auto text-gray-700 sm:h-20">
                        <a href="{{ route('dashboard') }}">
                            <img src="logo-long.png" class="block fill-current text-gray-600" />
                        </a>
                    </div>
                </div>

                <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow">
                    <div class="grid">
                        <div class="p-12">
                            <span class="text-indigo-500 leading-tight ">LaraVideoManager</span>
                            <p class="pb-5">Responsive web applicaiton to easy access and manage big file video/movie files.</p>

                            @auth
                                <x-a href="{{ url('/home') }}" class="">Go to Dashboard</x-a>
                            @else
                                <x-a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Log in</x-a>

                                @if (Route::has('register'))
                                    <x-a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">Register</x-a>
                                @endif
                            @endauth

                        </div>
                    </div>
                </div>


            </div>
        </div>



    </body>
</html>
