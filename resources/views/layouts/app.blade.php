<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />

        {{-- FontAwesome Icons --}}
        <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('assets/css/soft-ui-dashboard-tailwind.css') }}" />

        {{-- Nucleo Icons --}}
        <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet"/>
        <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
        {{-- <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.css" rel="stylesheet"> --}}

        @livewireStyles

        @vite('resources/css/app.css')
        <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
        {{-- <script src="//unpkg.com/alpinejs" defer></script> --}}

        {{-- <script src="https://unpkg.com/livewire-tables@latest/dist/livewire-tables.js" defer></script> --}}

        @stack('style')
        
    </head>

    <body class="m-0 overflow-x-hidden font-sans antialiased font-normal text-base leading-default bg-gray-50 text-slate-500">
        @include('template.sidebar')

        <main class="ease-soft-in-out xl:ml-68.5 relative h-full max-h-screen rounded-xl transition-all duration-200 overflow-x-hidden">
            @include('template.navbar')
            <div class="w-full px-6 py-6 mx-auto overflow-x-hidden">
                @livewire('misc.flash-message')
                @yield('content')
            </div>
        </main>

        <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}" async></script>
        <script src="{{ asset('assets/js/soft-ui-dashboard-tailwind.js') }}" async></script>        
        @vite('resources/js/app.js')
        @livewireScripts
    </body>
</html>