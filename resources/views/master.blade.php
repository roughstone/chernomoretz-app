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
    <link href="https://fonts.googleapis.com/css?family=Amatic+SC|Bad+Script|Caveat|Cormorant+Infant|Kelly+Slab|Lobster|Seymour+One|Stalinist+One" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
    <body>
        <div class="flex-center position-ref full-height" id="app">
            @if (Route::has('login'))
            <div class="loginPanel fixed-top">
                @auth
                    <a href="{{ url('/Профил') }}"><i class="far fa-user green"></i> Моят профил &#160</a>
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();"><i class="fas fa-power-off red"></i>
                            {{ __('Изход') }}
                        </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                @else
                    <a href="" class="fixed-top-right" data-toggle="modal" data-target="#userModal">
                        <i class="fas fa-sign-in-alt green"></i></i>&#160Вход
                    </a>
                @endauth
            </div>
            @endif
            <authentication-component></authentication-component>
            <master-header></master-header>
            <master-navigation></master-navigation>
            <div class="container-fluid">
            <contacts-component></contacts-component>
                <router-view></router-view>
            </div>
        </div>
@auth
    <script>
        window.user = @json(auth()->user())
    </script>
@endauth

</body>
</html>
