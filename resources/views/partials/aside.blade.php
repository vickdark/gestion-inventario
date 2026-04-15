<aside class="app-sidebar">
    <div class="app-sidebar-inner">
        <div class="app-sidebar-brand px-4 py-3 d-flex align-items-center gap-3">
            @php $logo = \App\Models\Setting::getByKey('app_logo'); @endphp
            @if($logo)
                <div class="app-brand-logo bg-white rounded-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px; min-width: 40px;">
                    <img src="{{ asset('storage/' . $logo) }}" alt="Logo" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                </div>
            @else
                <div class="app-brand-logo bg-primary bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px; min-width: 40px;">
                    <i class="fa-solid fa-rocket text-primary fs-4"></i>
                </div>
            @endif
            <div class="app-brand-info overflow-hidden">
                <span class="app-brand-text fw-bold text-white fs-5 lh-1 d-block">{{ \App\Models\Setting::getByKey('app_name', config('app.name', 'Laravel')) }}</span>
                <span class="text-sidebar-muted fw-medium" style="font-size: 0.65rem; letter-spacing: 0.05em; text-transform: uppercase;">{{ \App\Models\Setting::getByKey('sidebar_title', 'Administración de Inventario') }}</span>
            </div>
        </div>

        <nav class="nav flex-column app-sidebar-nav" id="sidebarAccordion">
            @php
                $user = auth()->user();
                $role = $user ? $user->role : null;
                $allPermissions = $role 
                    ? $role->permissions()
                        ->where('is_menu', true)
                        ->orderBy('order')
                        ->get()
                    : collect();
            @endphp

            @if($allPermissions->isEmpty())
                <div class="p-3 text-muted small">
                    <i class="fa-solid fa-circle-info me-1"></i>
                    No hay opciones de menú disponibles.
                </div>
            @endif

            @foreach($allPermissions as $item)
                @php
                    // Un ítem es activo si la ruta actual coincide exactamente con su slug
                    $isActive = request()->routeIs($item->slug);
                @endphp
                <a class="nav-link {{ $isActive ? 'active' : '' }}" href="{{ Route::has($item->slug) ? route($item->slug) : '#' }}">
                    <i class="{{ $item->icon ?: 'fa-solid fa-circle-dot' }}"></i>
                    <span class="app-link-text">{{ $item->nombre }}</span>
                </a>
            @endforeach
        </nav>

        <div class="app-sidebar-footer">
            @auth
            <div class="app-user-card d-flex align-items-center gap-3 mb-3" style="color: white !important;">
                <div class="app-user-avatar d-flex align-items-center justify-content-center shadow-sm">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="app-user-info overflow-hidden">
                    <div class="fw-bold text-white text-truncate small">{{ auth()->user()->name ?? 'Usuario' }}</div>
                    <div class="text-sidebar-muted text-truncate" style="font-size: 0.7rem;">
                        <i class="fa-solid fa-shield-halved me-1 text-primary opacity-75"></i>
                        {{ optional(auth()->user()->role)->nombre ?? 'Sin Rol' }}
                    </div>
                </div>
            </div>
            
            <form id="logout-form-aside" method="POST" action="{{ route('logout') }}" class="d-none">
                @csrf
            </form>
            
            <button class="btn logout-btn w-100 d-flex align-items-center justify-content-center gap-2 py-2 rounded-3 shadow-sm" type="button" onclick="handleLogout('logout-form-aside')" style="background-color: #dc3545 !important; color: white !important;">
                <i class="fa-solid fa-power-off small"></i>
                <span class="app-link-text fw-semibold small">Cerrar sesión</span>
            </button>
            @endauth
        </div>
    </div>
</aside>

<script>
/**
 * Maneja el cierre de sesión utilizando el módulo global Notify (Notifications.js)
 * @param {string} formId ID del formulario a enviar
 */
async function handleLogout(formId) {
    const confirmed = await window.Notify.confirm({
        title: '¿Cerrar sesión?',
        text: 'Tu sesión actual se finalizará.',
        confirmButtonText: 'Sí, salir',
        cancelButtonText: 'Mantenerse'
    });

    if (confirmed) {
        document.getElementById(formId).submit();
    }
}

/**
 * Maneja el cambio de contraseña mediante un formulario en SweetAlert2
 */
async function handleChangePassword() {
    const { value: formValues } = await Swal.fire({
        title: 'Cambiar Contraseña',
        html: `
            <div class="text-start mb-3">
                <label class="form-label small fw-bold">Contraseña Actual</label>
                <input type="password" id="current_password" class="form-control" placeholder="Ingrese su contraseña actual">
            </div>
            <div class="text-start mb-3">
                <label class="form-label small fw-bold">Nueva Contraseña</label>
                <input type="password" id="password" class="form-control" placeholder="Mínimo 8 caracteres">
            </div>
            <div class="text-start">
                <label class="form-label small fw-bold">Confirmar Nueva Contraseña</label>
                <input type="password" id="password_confirmation" class="form-control" placeholder="Repita la nueva contraseña">
            </div>
        `,
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonText: 'Actualizar Contraseña',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#c05a1e',
        preConfirm: () => {
            const current_password = document.getElementById('current_password').value;
            const password = document.getElementById('password').value;
            const password_confirmation = document.getElementById('password_confirmation').value;

            if (!current_password || !password || !password_confirmation) {
                Swal.showValidationMessage('Por favor complete todos los campos');
                return false;
            }

            if (password.length < 8) {
                Swal.showValidationMessage('La nueva contraseña debe tener al menos 8 caracteres');
                return false;
            }

            if (password !== password_confirmation) {
                Swal.showValidationMessage('Las contraseñas no coinciden');
                return false;
            }

            return { current_password, password, password_confirmation };
        }
    });

    if (formValues) {
        try {
            window.Notify.loading('Actualizando contraseña...');
            
            const response = await fetch('{{ route("password.update.ajax") }}', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formValues)
            });

            const data = await response.json();

            if (data.success) {
                window.Notify.success(data.message);
            } else {
                // Manejar errores de validación de Laravel
                let errorMessage = data.message || 'Error al actualizar la contraseña';
                if (data.errors) {
                    const firstError = Object.values(data.errors)[0][0];
                    errorMessage = firstError;
                }
                window.Notify.error(errorMessage);
            }
        } catch (error) {
            window.Notify.error('Ocurrió un error en la conexión');
            console.error(error);
        }
    }
}
</script>
