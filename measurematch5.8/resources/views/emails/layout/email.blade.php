<div class="container">
    <header class="row">
        @include('emails.includes.header')
    </header>

    <div id="main" class="row">

        @yield('content')

    </div>

    <footer class="row">
        @include('emails.includes.footer')
    </footer>

</div>
