<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ $globalSettings['site_name'] }} - Admin</title>
  <link rel="icon" href="{{ $globalSettings['site_favicon'] }}" type="image/x-icon">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

  <!-- Scripts -->
  @vite(['resources/css/app.css'])

  <!-- Alpine.js -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <!-- Quill.js for Rich Text Editor -->
  <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
  <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

  <style>
    [x-cloak] {
      display: none !important;
    }
  </style>
</head>

<body class="flex h-full overflow-hidden bg-gray-50 font-sans antialiased" x-data="{ sidebarOpen: true }">

  <!-- Sidebar -->
  <x-admin.sidebar />

  <!-- Main Content Wrapper -->
  <div class="relative flex h-screen flex-1 flex-col overflow-hidden">

    <!-- Header -->
    <x-admin.header>
      @yield('header')
    </x-admin.header>

    <!-- Content Scroller -->
    <main class="flex-1 overflow-y-auto bg-gray-50 p-4">
      <div class="max-w-8xl mx-auto space-y-2">
        @yield('content')
      </div>
      <!-- Footer -->
      <x-admin.footer />
    </main>

  </div>

  <!-- Toast Notifications -->
  <x-admin.ui.toast />
</body>

</html>
