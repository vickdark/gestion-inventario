<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ \App\Models\Setting::getByKey('app_name', config('app.name', 'Laravel')) }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-light d-flex align-items-center min-vh-100" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
        <main class="container py-5">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
                    <div class="text-center mb-4">
                        @php $logo = \App\Models\Setting::getByKey('app_logo'); @endphp
                        @if($logo)
                            <div class="bg-white rounded-4 d-inline-flex align-items-center justify-content-center shadow-sm mb-3" style="width: 80px; height: 80px; padding: 10px;">
                                <img src="{{ asset('storage/' . $logo) }}" alt="Logo" class="img-fluid" style="max-height: 100%; object-fit: contain;">
                            </div>
                        @else
                            <div class="bg-primary bg-opacity-10 rounded-4 d-inline-flex align-items-center justify-content-center shadow-sm mb-3" style="width: 60px; height: 60px;">
                                <i class="fa-solid fa-rocket text-primary fs-2"></i>
                            </div>
                        @endif
                        <h2 class="fw-bold text-dark mb-1">{{ \App\Models\Setting::getByKey('app_name', config('app.name', 'Gestion de Inventario')) }}</h2>
                        <p class="text-muted small text-uppercase fw-semibold" style="letter-spacing: 0.05em;">{{ \App\Models\Setting::getByKey('sidebar_title', 'Administración de Eventos') }}</p>
                    </div>
                    <div class="p-4 p-md-5 bg-white border-0 rounded-4 shadow-lg">
                        @yield('content')
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>
