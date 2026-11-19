<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Idea</title>
     @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-background text-foreground">
    <x-layout.nav />
    <main class="max-w-7xl mx-auto px-6 py-6">
        {{ $slot }}
    </main>
@session('success')
    <div
        x-data="{ show: false }"
        x-init="
            requestAnimationFrame(() => show = true);
            setTimeout(() => show = false, 3000);
        "
        x-show="show"

        x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="translate-x-full opacity-0"
        x-transition:enter-end="translate-x-0 opacity-100"

        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="translate-x-0 opacity-100"
        x-transition:leave-end="translate-x-full opacity-0"

        class="bg-primary px-4 py-3 fixed bottom-4 right-4 z-50 rounded-lg"
    >
        {{ $value }}
    </div>
@endsession
</body>

</html>
