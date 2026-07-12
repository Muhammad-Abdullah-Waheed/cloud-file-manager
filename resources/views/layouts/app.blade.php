<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}"
      dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-base-100">

    @include('components.navbar')

    <main>
        @yield('content')
    </main>

    <footer class="footer footer-center p-4 bg-base-300 text-base-content">
        <p>{{ config('app.name') }} © {{ date('Y') }}</p>
    </footer>

</body>
</html>
