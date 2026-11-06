<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>@yield('title', 'Aura Material')</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

  @vite(['resources/css/app.css','resources/js/sidebar.js'])
  @stack('head')
</head>
<body class="font-['Plus_Jakarta_Sans',sans-serif] @yield('body_class', 'bg-[#f8f9ff] text-[#0b1c30]')">

  @include('partials.admin-sidebar')

  {{-- Lapisan gelap di belakang sidebar saat dibuka di HP/tablet --}}
  <div id="sidebarBackdrop" onclick="closeSidebar()"
       class="fixed inset-0 bg-[#000000]/50 backdrop-blur-sm z-40 hidden lg:hidden"></div>

  <div class="pl-0 min-h-screen flex flex-col transition-all duration-200 lg:pl-64">
    @include('partials.admin-navbar')

    <main class="relative pt-16 w-full flex-1">
      @yield('content')
    </main>
  </div>

  {{-- Modal / pop-up milik tiap halaman --}}
  @stack('modals')
</body>
</html>