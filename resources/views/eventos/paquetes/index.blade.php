@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <h3 class="fw-bold mb-0">Gestión de Paquetes</h3>
        <a href="{{ route('eventos.paquetes.create') }}" class="btn btn-primary rounded-3 px-4 shadow-sm fw-bold">
            <i class="fa-solid fa-plus me-2"></i> Crear Nuevo Paquete
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <i class="fa-solid fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        @forelse($paquetes as $paquete)
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden shadow-soft position-relative">
                @php
                    $imageSrc = $paquete->imagen ? (str_starts_with($paquete->imagen, 'http') ? $paquete->imagen : asset('storage/' . $paquete->imagen)) : null;
                @endphp
                
                @if($imageSrc)
                    <div class="bg-light" style="height: 160px;">
                        <img src="{{ $imageSrc }}" alt="{{ $paquete->nombre }}" class="w-100 h-100 object-fit-cover">
                    </div>
                @endif

                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-4">
                            <i class="fa-solid fa-box-open text-primary fs-4"></i>
                        </div>
                        <div class="text-end">
                            <div class="small text-muted">Precio Día / Hora</div>
                            <h5 class="fw-bold text-primary mb-0">${{ number_format($paquete->precio_dia) }} / ${{ number_format($paquete->precio_hora) }}</h5>
                        </div>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">{{ $paquete->nombre }}</h5>
                    <p class="text-muted small mb-4 line-clamp-2">
                        {{ $paquete->descripcion ?: 'Sin descripción disponible.' }}
                    </p>
                    
                    <div class="mb-4">
                        <span class="small fw-bold text-muted text-uppercase mb-2 d-block">Equipos incluidos:</span>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($paquete->equipos->take(3) as $equipo)
                                <span class="badge bg-light text-dark border rounded-pill px-2 py-1" style="font-size: 0.7rem;">
                                    {{ $equipo->pivot->cantidad }}x {{ $equipo->nombre }}
                                </span>
                            @endforeach
                            @if($paquete->equipos->count() > 3)
                                <span class="badge bg-light text-muted border rounded-pill px-2 py-1" style="font-size: 0.7rem;">
                                    +{{ $paquete->equipos->count() - 3 }} más
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('eventos.paquetes.show', $paquete->id) }}" class="btn btn-outline-primary rounded-3 flex-grow-1 fw-bold shadow-sm">
                            <i class="fa-solid fa-eye me-2"></i> Detalles
                        </a>
                        <a href="{{ route('eventos.paquetes.edit', $paquete->id) }}" class="btn btn-light rounded-3 shadow-sm border">
                            <i class="fa-solid fa-pen text-primary"></i>
                        </a>
                        <form action="{{ route('eventos.paquetes.destroy', $paquete->id) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-light rounded-3 shadow-sm border btn-delete-paquete">
                                <i class="fa-solid fa-trash text-danger"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="fa-solid fa-box-archive fs-1 text-muted opacity-25 mb-3 d-block"></i>
            <h5 class="text-muted">No has creado ningún paquete todavía.</h5>
            <a href="{{ route('eventos.paquetes.create') }}" class="btn btn-primary rounded-pill px-4 mt-3">Crear mi primer paquete</a>
        </div>
        @endforelse
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-delete-paquete').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('.delete-form');
                window.Notify.confirm({
                    title: '¿Eliminar paquete?',
                    text: 'Esta acción no se puede deshacer.',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection
