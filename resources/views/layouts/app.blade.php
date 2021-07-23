<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- SEO -->
        <title>Mail Client</title>
        <meta name="keywords" content="" />
        <meta name="description" content="">

        <!-- Fonts -->    
        <link href="{{ asset('fonts/Montserrat-Regular.ttf') }}">
        <link href="{{ asset('fonts/Montserrat-SemiBold.ttf') }}">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>
        <div id="app">
            <main>
                @yield('content')
            </main>
        </div>

        <!-- Scripts -->           
        <script src="{{ asset('js/app.js') }}"></script>       
    </body>
</html>