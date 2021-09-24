<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

<body class="antialiased">
<h1>Aqui va la navegacion</h1>
{{-- @include('partials.header') bloquea el servidor --}}
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="/">Inicio <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/store">Tienda</a>
            </li>
        </ul>
    </div>
</nav>
 @yield('content', 'Main Content')

<script src="{{ asset('js/app.js') }}" type="javascript"></script>
</body>
</html>
