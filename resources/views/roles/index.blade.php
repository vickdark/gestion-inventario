@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h1 class="h3 mb-0 text-gray-800">Roles del Sistema</h1>
        </div>
        <div class="col-auto">
            <div class="d-flex gap-2">
                @if(auth()->user()->hasPermission('permissions.sync'))
                    <form action="{{ route('permissions.sync') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-info rounded-pill px-4">
                            <i class="fas fa-sync me-2"></i> Sincronizar Permisos
                        </button>
                    </form>
                @endif
                @if(auth()->user()->hasPermission('roles.create'))
                    <a href="{{ route('roles.create') }}" class="btn btn-primary rounded-pill px-4">
                        <i class="fas fa-plus me-2"></i> Nuevo Rol
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-soft rounded-4 overflow-hidden">
        <div class="card-body p-4">
            <div id="roles-wrapper"></div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        initRolesIndex({
            routes: {
                index: "{{ route('roles.index') }}",
                edit: "{{ route('roles.edit', ':id') }}",
                destroy: "{{ route('roles.destroy', ':id') }}",
                permissions: "{{ route('roles.edit_permissions', ':id') }}"
            },
            tokens: {
                csrf: "{{ csrf_token() }}"
            }
        });
    });
</script>
@endsection
