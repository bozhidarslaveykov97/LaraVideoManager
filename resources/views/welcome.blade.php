<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>LaraVideoManager</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

</head>
<body>

<div class="container" style="margin-top: 200px;">
    <div class="d-flex align-items-center justify-content-center">

    <div class="row">
    <div class="col-md-12">
        <a href="{{ route('dashboard') }}">
            <img src="logo-long.png" class="pb-5" />
        </a>
    </div>

    <div class="col-md-12">

        <div class="card">
        <div class="card-body">
        <h3>Lara Video Manager</h3>
        <p>Responsive web applicaiton to easy access and manage big file video/movie files.</p>

        <br />

        @auth
            <x-a href="{{ url('/home') }}" class="btn btn-outline-primary">Go to Dashboard</x-a>
        @else
            <x-a href="{{ route('login') }}" class="btn btn-outline-primary">Log in</x-a>

            @if (Route::has('register'))
                <x-a href="{{ route('register') }}" class="btn btn-outline-primary">Register</x-a>
            @endif
        @endauth
        </div>
        </div>

    </div>
    </div>
</div>
</div>
</body>
</html>
