@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <h3 class="fw-bold mb-0">Gestión de Ventas / Facturación</h3>
        <a href="{{ route('eventos.ventas.create') }}" class="btn btn-primary rounded-3 px-4 shadow-sm fw-bold">
            <i class="fa-solid fa-plus me-2"></i> Nueva Venta
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div id="grid-ventas"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ventas = @json($ventas);
        
        new DataGrid("grid-ventas", {
            columns: [
                { id: 'id', name: 'ID', hidden: true },
                { id: 'factura', name: 'N° Factura', 
                    formatter: (cell) => DataGrid.html(`<span class="fw-bold text-success">${cell}</span>`)
                },
                { id: 'cliente', name: 'Cliente' },
                { id: 'fecha', name: 'Fecha Venta' },
                { id: 'total', name: 'Total',
                    formatter: (cell) => DataGrid.html(`<span class="fw-bold">$${new Intl.NumberFormat('es-CO').format(cell)}</span>`)
                },
                { id: 'pago', name: 'Estado Pago',
                    formatter: (cell) => {
                        let badgeClass = 'bg-secondary';
                        if(cell === 'pendiente') badgeClass = 'bg-warning';
                        if(cell === 'pagado') badgeClass = 'bg-success';
                        if(cell === 'parcial') badgeClass = 'bg-info';
                        if(cell === 'anulado') badgeClass = 'bg-danger';
                        
                        return DataGrid.html(`<span class="badge ${badgeClass} bg-opacity-10 text-${badgeClass.replace('bg-', '')} rounded-pill px-3 py-2 text-capitalize">${cell}</span>`);
                    }
                },
                { 
                    name: 'Acciones',
                    formatter: (cell, row) => {
                        const id = row.cells[0].data;
                        return DataGrid.html(`
                            <div class="d-flex justify-content-end gap-2">
                                <a href="/eventos/ventas/${id}" class="btn btn-sm btn-light rounded-3" title="Ver Detalle">
                                    <i class="fa-solid fa-eye text-primary"></i>
                                </a>
                                <a href="/eventos/ventas/${id}/factura" target="_blank" class="btn btn-sm btn-light rounded-3" title="Imprimir Factura">
                                    <i class="fa-solid fa-file-invoice text-success"></i>
                                </a>
                                <a href="/eventos/ventas/${id}/edit" class="btn btn-sm btn-light rounded-3" title="Editar">
                                    <i class="fa-solid fa-pen text-info"></i>
                                </a>
                                <form action="/eventos/ventas/${id}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-light rounded-3 btn-delete" title="Eliminar">
                                        <i class="fa-solid fa-trash text-danger"></i>
                                    </button>
                                </form>
                            </div>
                        `);
                    }
                }
            ],
            data: ventas.map(v => ({
                id: v.id,
                factura: v.numero_factura,
                cliente: v.cliente ? v.cliente.nombre : 'N/A',
                fecha: new Date(v.fecha_venta).toLocaleDateString(),
                total: v.total,
                pago: v.estado_pago
            })),
            search: true,
            pagination: { limit: 10 },
            sort: true,
            language: {
                search: { placeholder: 'Buscar venta/factura...' },
                pagination: {
                    previous: 'Anterior',
                    next: 'Siguiente',
                    showing: 'Mostrando',
                    results: () => 'resultados'
                }
            },
            style: { table: { 'white-space': 'nowrap' } }
        }).render(document.getElementById('grid-ventas'));

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
