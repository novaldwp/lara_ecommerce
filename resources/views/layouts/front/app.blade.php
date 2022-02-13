@include('layouts.front.header')
    <body>
        @include('layouts.front.top')

        @include('layouts.front.navbar')

        @yield('content')

        @include('layouts.front.footer')

        @include('sweetalert::alert')

        <script>
            $(document).ready(function() {
                $(document).on('click', 'a.fakeLogoutButton', function(e){
                    e.preventDefault();
                    $('.logoutButton').click();
                })
            });
            </script>
