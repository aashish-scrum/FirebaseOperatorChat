<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased" id="app">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')
            <main>
                <div class="container mt-3">
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <chat :user="{{ Auth::user() }}"/>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>
