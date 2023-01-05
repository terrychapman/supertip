<!doctype html>
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
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>
<body>
    <!-- SVG data - used for svg graphics -->
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <symbol id="facebook" viewBox="0 0 16 16">
            <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
        </symbol>
    </svg>
    <div id="app">
        <!-- Header -->
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <img src="/images/favicon-32x32.png">&nbsp;
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="/images/st-logo-words.png" width="120px" height="25px">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/') }}">{{ __('Home') }}</a>
                            </li>    
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('ladder') }}">{{ __('Ladder') }}</a>
                            </li>    
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('rules') }}">{{ __('Rules') }}</a>
                            </li>
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/') }}">{{ __('Home') }}</a>
                            </li> 
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('ladder') }}">{{ __('Ladder') }}</a>
                            </li> 
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('rules') }}">{{ __('Rules') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('tip') }}">{{ __('Tip') }}</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    @if (Auth::user()->email == 'terry@mychapman.com' || Auth::user()->email == 'peterdanielsmith@hotmail.com')
                                        <a class="dropdown-item" href="{{ route('games') }}">Games</a>
                                        <a class="dropdown-item" href="{{ route('import') }}">Import</a>
                                        <a class="dropdown-item" href="{{ route('userAdmin') }}">Users</a>
                                        <a class="dropdown-item" href="{{ route('ladderAdmin') }}">Ladder Admin</a>
                                    @endif
                                    <a class="dropdown-item" href="{{ route('profile') }}">Edit Profile</a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Pages content -->
        <main class="py-4">
            @yield('content')
        </main>

        <!-- Footer -->
        <div class="container">
            <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
                <div class="col-md-4 d-flex align-items-center">
                    <span class="text-muted">&copy; 2022 Terry Chapman</span>
                </div>
                
                <ul class="nav col-md-4 justify-content-end">
                    <li class="nav-item"><a href="https://www.nrl.com/news" class="nav-link px-2 text-muted" target="_blank">NRL News</a></li>
                    <li class="nav-item"><a href="https://www.nrl.com/draw/" class="nav-link px-2 text-muted" target="_blank">NRL Draw</a></li>
                    <li class="nav-item"><a href="mailto:terry@mychapman.com" class="nav-link px-2 text-muted">Contact Us</a></li>
                </ul>

                <ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
                    <li class="ms-3"><a class="text-muted" href="https://www.facebook.com/SuperTip-808504202628261/" target="_blank"><svg class="bi" width="24" height="24"><use xlink:href="#facebook"/></svg></a></li>
                </ul>
            </footer>
        </div>
    </div>
</body>
</html>
