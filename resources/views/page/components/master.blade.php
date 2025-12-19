<!DOCTYPE html>
<html lang="en">

@include('page.components.head')

<body class="index-page flex flex-col min-h-screen h-full bg-white overflow-x-hidden">
    @include('page.components.header')
    <main class="main flex-grow">
        @yield('content')
    </main>
    @include('page.components.footer')
</body>

</html>
