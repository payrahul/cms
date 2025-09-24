<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Panel')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.21.2/dist/bootstrap-table.min.css">


    @stack('styles') {{-- for page-specific styles --}}
</head>
<body>
    <div class="d-flex min-vh-100">
        {{-- Sidebar --}}
        @if (!View::hasSection('no-sidebar'))
            @include('layouts.sidebar')
        @endif
        

        {{-- Main content --}}
        <div class="flex-grow-1">
            {{-- Header --}}

            @if (!View::hasSection('no-header'))
                @include('layouts.header')
            @endif
            
            
            {{-- Page content --}}
            <main class="container py-4">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.js"></script>
    <!-- Bootstrap Table JS -->
    <script src="https://unpkg.com/bootstrap-table@1.21.2/dist/bootstrap-table.min.js"></script>
    <script src="https://unpkg.com/bootstrap-table@1.21.2/dist/extensions/export/bootstrap-table-export.min.js"></script>
    <script src="https://unpkg.com/tableexport.jquery.plugin/tableExport.min.js"></script>

    @stack('scripts') {{-- for page-specific scripts --}}
</body>
</html>
