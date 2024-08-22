<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite('resources/css/app.css')
</head>
<body class="antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Main Content -->
        <main>
            @yield('content') <!-- General content section -->
        </main>
        
        <!-- Form Section -->
        <section>
            @yield('form') <!-- Specific section for form -->
        </section>
    </div>
    @vite('resources/js/app.js')
</body>
</html>