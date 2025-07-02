<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Karib</title>
        <link rel="icon" href="../assets/img/favicon.ico" type="image/x-icon">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="font-sans text-gray-900 antialiased bg-[#fffdf6]">

        <!-- Logo + Nama Aplikasi -->
        <div class="absolute top-6 left-6 sm:top-10 sm:left-10 flex flex-col items-start z-50">
            <div class="flex items-center space-x-3">
                <img src="../assets/img/logo.png" alt="Logo Karib" class="w-10 sm:w-16">
                <span class="text-2xl sm:text-5xl font-bold text-[#b7279e]">Karib</span>
            </div>
            <!-- Tampilkan slogan hanya di layar sedang ke atas -->
            <p class="hidden sm:block text-gray-600 mt-2 tracking-wide font-semibold text-lg leading-none">BPS PROVINSI ACEH</p>
        </div>

        <!-- Layout utama -->
        <div id="heroContainer" class="min-h-screen flex flex-col sm:flex-row bg-contain bg-left bg-no-repeat" style="background-image: url('../assets/img/hero.png');">
            <!-- Background illustration area (left, hanya muncul di layar besar) -->
            <div class="hidden sm:block sm:w-1/2">
                <!-- kosong -->
            </div>

            <!-- Form login -->
            <div class="w-full sm:w-1/2 flex justify-center items-center px-6 sm:ml-28 min-h-screen sm:min-h-0">
                <div class="w-full sm:max-w-md px-6 py-6 bg-white shadow-md overflow-hidden rounded-lg">
                    {{ $slot }}
                </div>
            </div>
        </div>

        <script>
            function handleBackground() {
                const hero = document.getElementById('heroContainer');
                if (window.innerWidth < 640) { // < 640px (Tailwind's sm breakpoint)
                    hero.style.backgroundImage = 'none';
                } else {
                    hero.style.backgroundImage = "url('../assets/img/hero.png')";
                }
            }

            // Jalankan saat pertama kali halaman load
            handleBackground();

            // Jalankan juga saat window di-resize
            window.addEventListener('resize', handleBackground);
        </script>

    </body>

</html>
