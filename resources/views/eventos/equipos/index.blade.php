@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <h3 class="fw-bold mb-0">Inventario de Equipos</h3>
        <div class="d-flex gap-2">
            <div class="input-group me-2" style="max-width: 300px;">
                <span class="input-group-text bg-white border-end-0 rounded-start-3"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                <input type="text" id="search-equipos" class="form-control border-start-0 rounded-end-3" placeholder="Buscar equipo...">
            </div>
            <a href="{{ route('eventos.equipos.create') }}" class="btn btn-primary rounded-3 px-4 shadow-sm fw-bold">
                <i class="fa-solid fa-plus me-2"></i> Nuevo Equipo
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <i class="fa-solid fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filtros por Categoría -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('eventos.equipos.index') }}" class="btn btn-sm {{ !request('categoria') ? 'btn-primary' : 'btn-light border' }} rounded-pill px-4 fw-bold">Todos</a>
                @foreach($categorias as $cat)
                    <a href="{{ route('eventos.equipos.index', ['categoria' => $cat->slug]) }}" class="btn btn-sm {{ request('categoria') == $cat->slug ? 'btn-primary' : 'btn-light border' }} rounded-pill px-4 fw-bold">{{ $cat->nombre }}</a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="row g-4" id="equipos-container">
        @forelse($equipos as $equipo)
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3 equipo-card" data-nombre="{{ strtolower($equipo->nombre) }}" data-descripcion="{{ strtolower($equipo->descripcion) }}">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 shadow-soft position-relative">
                <div class="position-relative">
                    @php
                        $imageSrc = $equipo->imagen ? (str_starts_with($equipo->imagen, 'http') ? $equipo->imagen : asset('storage/' . $equipo->imagen)) : null;
                    @endphp
                    @if($imageSrc)
                        <div class="bg-light d-flex align-items-center justify-content-center overflow-hidden" style="height: 180px;">
                            <img src="{{ $imageSrc }}" alt="{{ $equipo->nombre }}" class="w-100 h-100 object-fit-cover">
                        </div>
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                            <i class="fa-solid fa-image text-muted fs-1 opacity-25"></i>
                        </div>
                    @endif
                    <div class="position-absolute top-0 end-0 m-3">
                        @php
                            $statusClass = match($equipo->estado) {
                                'Disponible' => 'bg-success',
                                'Mantenimiento' => 'bg-warning text-dark',
                                'Baja' => 'bg-danger',
                                default => 'bg-secondary'
                            };
                        @endphp
                        <span class="badge {{ $statusClass }} rounded-pill px-3 py-2 shadow-sm">
                            {{ $equipo->estado }}
                        </span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="small text-primary fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.05em;">
                            {{ $equipo->categoria->nombre }}
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3">
                                <li><a class="dropdown-item py-2" href="{{ route('eventos.equipos.edit', $equipo->id) }}"><i class="fa-solid fa-pen me-2 small"></i> Editar</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('eventos.equipos.destroy', $equipo->id) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="dropdown-item py-2 text-danger btn-delete-equipo"><i class="fa-solid fa-trash me-2 small"></i> Eliminar</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <h5 class="fw-bold text-dark mb-3">{{ $equipo->nombre }}</h5>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <div class="small text-muted">Precio Día / Hora</div>
                            <div class="fw-bold text-dark fs-6">
                                ${{ number_format($equipo->precio_dia) }} / ${{ number_format($equipo->precio_hora) }}
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="small text-muted">Stock</div>
                            <div class="fw-bold {{ $equipo->cantidad_disponible > 0 ? 'text-dark' : 'text-danger' }} fs-5">
                                {{ $equipo->cantidad_disponible }} / {{ $equipo->cantidad_total }}
                            </div>
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="{{ route('eventos.equipos.show', $equipo->id) }}" class="btn btn-outline-primary btn-sm rounded-3 fw-bold py-2">
                            <i class="fa-solid fa-eye me-1"></i> Ver Detalles
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="fa-solid fa-box-open fs-1 text-muted opacity-25 mb-3 d-block"></i>
            <h5 class="text-muted">No se encontraron equipos en esta categoría.</h5>
        </div>
        @endforelse
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Buscador dinámico
        const searchInput = document.getElementById('search-equipos');
        const cards = document.querySelectorAll('.equipo-card');
        const noResults = document.createElement('div');
        noResults.className = 'col-12 text-center py-5 d-none';
        noResults.innerHTML = `
            <i class="fa-solid fa-magnifying-glass fs-1 text-muted opacity-25 mb-3 d-block"></i>
            <h5 class="text-muted">No se encontraron equipos que coincidan con tu búsqueda.</h5>
        `;
        document.getElementById('equipos-container').appendChild(noResults);

        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            let hasResults = false;

            cards.forEach(card => {
                const nombre = card.dataset.nombre;
                const descripcion = card.dataset.descripcion;
                
                if (nombre.includes(query) || descripcion.includes(query)) {
                    card.classList.remove('d-none');
                    hasResults = true;
                } else {
                    card.classList.add('d-none');
                }
            });

            if (hasResults) {
                noResults.classList.add('d-none');
            } else {
                noResults.classList.remove('d-none');
            }
        });

        // Confirmación de eliminación
        document.querySelectorAll('.btn-delete-equipo').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('.delete-form');
                window.Notify.confirm({
                    title: '¿Eliminar equipo?',
                    text: 'Esta acción no se puede deshacer y el equipo se borrará permanentemente.',
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
@endsection
