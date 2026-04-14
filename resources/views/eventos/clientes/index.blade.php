@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h3 class="fw-bold mb-0">Gestión de Clientes</h3>
        <button class="btn btn-primary rounded-3 px-4 shadow-sm fw-bold">
            <i class="fa-solid fa-plus me-2"></i> Nuevo Cliente
        </button>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-muted small text-uppercase border-0">Cliente</th>
                            <th class="py-3 text-muted small text-uppercase border-0">Teléfono</th>
                            <th class="py-3 text-muted small text-uppercase border-0">Dirección</th>
                            <th class="py-3 text-muted small text-uppercase border-0">Tipo</th>
                            <th class="py-3 text-muted small text-uppercase border-0">Notas</th>
                            <th class="pe-4 py-3 text-muted small text-uppercase border-0 text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clientes as $cliente)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
                                        <i class="fa-solid fa-user text-primary"></i>
                                    </div>
                                    <div class="fw-bold">{{ $cliente['nombre'] }}</div>
                                </div>
                            </td>
                            <td>
                                <a href="https://wa.me/{{ $cliente['telefono'] }}" target="_blank" class="text-decoration-none text-success fw-bold">
                                    <i class="fa-brands fa-whatsapp me-1"></i> {{ $cliente['telefono'] }}
                                </a>
                            </td>
                            <td>{{ $cliente['direccion'] }}</td>
                            <td>
                                <span class="badge {{ $cliente['tipo'] === 'Empresa' ? 'bg-purple bg-opacity-10 text-purple' : 'bg-blue bg-opacity-10 text-blue' }} rounded-pill px-3 py-2">
                                    {{ $cliente['tipo'] }}
                                </span>
                            </td>
                            <td><span class="text-muted small italic">{{ $cliente['notes'] ?? 'Sin notas' }}</span></td>
                            <td class="pe-4 text-end">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-light rounded-3 px-3 fw-bold text-primary" title="Cotizar">
                                        <i class="fa-solid fa-file-invoice-dollar me-1"></i> Cotizar
                                    </button>
                                    <button class="btn btn-sm btn-light rounded-3 mx-1" title="Editar">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <button class="btn btn-sm btn-light rounded-3" title="Eliminar">
                                        <i class="fa-solid fa-trash text-danger"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
