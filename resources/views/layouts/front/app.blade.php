@include('layouts.front.header')
    <body>
        @include('layouts.front.top')

        @include('layouts.front.navbar')

        @yield('content')

        @include('layouts.front.footer')
