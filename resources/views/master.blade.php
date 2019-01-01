<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
    <body>
        <div class="flex-center position-ref full-height" id="app">
                @if (Route::has('login'))
                <div class="loginPanel">
                    @auth
                        <a href="{{ url('/home') }}"><i class="far fa-user green"></i> Моят профил &#160</a>
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();"><i class="fas fa-power-off red"></i>
                                {{ __('Изход') }}
                            </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @else
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-toggle="modal" data-target="#userModal">
                            <i class="fas fa-sign-in-alt green"></i></i>&#160Вход
                        </button>
                    @endauth
                </div>
                @endif
                <authentication-component></authentication-component>
                <master-header></master-header>
                <master-navigation></master-navigation>
            <div class="content">
                <router-view></router-view>
            </div>
        </div>
    </body>
</html>
