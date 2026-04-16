@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <h3 class="fw-bold mb-0">Gestión de Cotizaciones</h3>
        <a href="{{ route('eventos.cotizaciones.create') }}" class="btn btn-primary rounded-3 px-4 shadow-sm fw-bold">
            <i class="fa-solid fa-plus me-2"></i> Nueva Cotización
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <i class="fa-solid fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div id="grid-cotizaciones"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cotizaciones = @json($cotizaciones);
        
        new DataGrid("grid-cotizaciones", {
            data: cotizaciones.map(c => [
                c.id,
                c.numero_cotizacion,
                c.cliente ? c.cliente.nombre : 'Sin cliente',
                new Date(c.fecha_emision).toLocaleDateString(),
                c.total,
                c.estado,
                c.id // ID para acciones
            ]),
            columns: [
                { id: 'id', name: 'ID', hidden: true },
                { 
                    id: 'numero', 
                    name: 'N° Cotización', 
                    formatter: (cell) => DataGrid.html(`<span class="fw-bold text-primary">${cell}</span>`)
                },
                { id: 'cliente', name: 'Cliente' },
                { id: 'fecha', name: 'Fecha Emisión' },
                { 
                    id: 'total', 
                    name: 'Total',
                    formatter: (cell) => DataGrid.html(`<span class="fw-bold">$${new Intl.NumberFormat('es-CO').format(cell)}</span>`)
                },
                { 
                    id: 'estado', 
                    name: 'Estado',
                    formatter: (cell) => {
                        let badgeClass = 'bg-secondary';
                        if(cell === 'pendiente') badgeClass = 'bg-warning';
                        if(cell === 'aprobada') badgeClass = 'bg-success';
                        if(cell === 'convertida') badgeClass = 'bg-info';
                        if(cell === 'rechazada') badgeClass = 'bg-danger';
                        
                        return DataGrid.html(`<span class="badge ${badgeClass} bg-opacity-10 text-${badgeClass.replace('bg-', '')} rounded-pill px-3 py-2 text-capitalize">${cell}</span>`);
                    }
                },
                { 
                    id: 'acciones',
                    name: 'Acciones',
                    sort: false,
                    formatter: (id, row) => {
                        const estado = row.cells[5].data;
                        let actions = `
                            <div class="d-flex justify-content-end gap-2">
                                <a href="/eventos/cotizaciones/${id}" class="btn btn-sm btn-light rounded-3" title="Ver Detalle">
                                    <i class="fa-solid fa-eye text-primary"></i>
                                </a>
                                <a href="/eventos/cotizaciones/${id}/comprobante" target="_blank" class="btn btn-sm btn-light rounded-3" title="Imprimir Cotización">
                                    <i class="fa-solid fa-file-invoice text-danger"></i>
                                </a>
                                <a href="/eventos/cotizaciones/${id}/edit" class="btn btn-sm btn-light rounded-3" title="Editar">
                                    <i class="fa-solid fa-pen text-info"></i>
                                </a>`;
                        
                        if(estado === 'pendiente') {
                            actions += `
                                <form action="/eventos/cotizaciones/${id}/convertir" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success rounded-3" title="Convertir a Venta">
                                        <i class="fa-solid fa-check-to-slot"></i>
                                    </button>
                                </form>`;
                        }

                        actions += `
                                <form action="/eventos/cotizaciones/${id}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-light rounded-3 btn-delete" title="Eliminar">
                                        <i class="fa-solid fa-trash text-danger"></i>
                                    </button>
                                </form>
                            </div>`;
                        return DataGrid.html(actions);
                    }
                }
            ]
        }).render();

        // Manejo de eliminación con SweetAlert
        document.addEventListener('click', function(e) {
            if (e.target.closest('.btn-delete')) {
                const form = e.target.closest('.delete-form');
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Esta acción no se puede deshacer",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
        });
    });
</script>
@endpush
