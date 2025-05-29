<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Catatan Keuangan')</title>

    @include('layouts.css')
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        @include('layouts.navbar')

        <div class="flex">
            @include('layouts.sidebar')

            <main class="flex-1 p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @include('layouts.footer')
    @include('layouts.js')
</body>
</html>
