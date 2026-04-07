<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SmarSchool' }} | SmarSchool</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-surface text-on-surface">
    @if (session('success'))
        <div data-flash data-flash-type="success" data-flash-text="{{ session('success') }}"></div>
    @endif

    @if ($errors->any())
        <div data-flash data-flash-type="error" data-flash-text="{{ $errors->first() }}"></div>
    @endif

    <div class="flex min-h-screen">
        <x-sidebar :page-key="$pageKey ?? null" />

        <main class="flex min-w-0 flex-1 flex-col">
            <x-top-nav :title="$title ?? null" />
            @yield('content')
        </main>
    </div>
</body>

</html>
