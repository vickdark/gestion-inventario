@extends('layouts.guest')

@section('content')
    <div class="text-center mb-4">
        <h3 class="fw-bold text-dark">¡Bienvenido de nuevo!</h3>
        <p class="text-muted small">Ingresa tus credenciales para acceder</p>
    </div>

    @if (session('status'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-4">
            <label class="form-label small fw-bold text-muted" for="email">Correo Electrónico</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-0"><i class="fa-solid fa-envelope text-muted"></i></span>
                <input
                    class="form-control bg-light border-0 @error('email') is-invalid @enderror"
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="ejemplo@correo.com"
                    required
                    autofocus
                    style="padding: 0.75rem;"
                >
            </div>
            @error('email')
                <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="form-label small fw-bold text-muted mb-0" for="password">Contraseña</label>
            </div>
            <div class="input-group">
                <span class="input-group-text bg-light border-0"><i class="fa-solid fa-lock text-muted"></i></span>
                <input
                    class="form-control bg-light border-0 @error('password') is-invalid @enderror"
                    id="password"
                    type="password"
                    name="password"
                    placeholder="••••••••"
                    required
                    style="padding: 0.75rem;"
                >
                <button class="btn btn-light border-0 px-3" type="button" id="togglePassword">
                    <i class="fa-solid fa-eye text-muted" id="toggleIcon"></i>
                </button>
            </div>
            @error('password')
                <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
            @enderror
        </div>

        <script>
            document.getElementById('togglePassword').addEventListener('click', function() {
                const password = document.getElementById('password');
                const icon = document.getElementById('toggleIcon');
                
                if (password.type === 'password') {
                    password.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    password.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        </script>

        <div class="mb-4">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label small text-muted" for="remember">Mantener sesión iniciada</label>
            </div>
        </div>

        <div class="d-grid gap-2">
            <button class="btn btn-primary py-3 fw-bold rounded-3 shadow-sm border-0" type="submit">
                Entrar al Sistema <i class="fa-solid fa-arrow-right-to-bracket ms-2"></i>
            </button>
            <div class="text-center mt-2">
                <a class="extra-small text-decoration-none fw-bold" href="{{ route('password.request') }}" style="font-size: 0.75rem;">
                    ¿Olvidaste tu contraseña?
                </a>
            </div>
        </div>
    </form>
@endsection
