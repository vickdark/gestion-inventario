@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h3 class="fw-bold mb-0">Gestión de Clientes</h3>
        <a href="{{ route('eventos.clientes.create') }}" class="btn btn-primary rounded-3 px-4 shadow-sm fw-bold">
            <i class="fa-solid fa-plus me-2"></i> Nuevo Cliente
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <i class="fa-solid fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div id="clientes-grid"></div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const clientes = @json($clientes);
        
        new DataGrid("clientes-grid", {
            data: clientes.map(c => [
                c.id,
                c.nombre,
                c.telefono,
                c.direccion,
                c.tipo,
                c.notas || 'Sin notas',
                c.id // Pasamos el ID también a la columna de acciones
            ]),
            columns: [
                { 
                    id: 'id',
                    name: "ID", 
                    hidden: true 
                },
                { 
                    id: 'nombre',
                    name: "Cliente", 
                    formatter: (cell) => DataGrid.html(`<div class="d-flex align-items-center"><div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3"><i class="fa-solid fa-user text-primary"></i></div><div class="fw-bold">${cell || 'Sin nombre'}</div></div>`) 
                },
                { 
                    id: 'telefono',
                    name: "Teléfono", 
                    formatter: (cell) => DataGrid.html(cell ? `<a href="https://wa.me/${cell}" target="_blank" class="text-decoration-none text-success fw-bold"><i class="fa-brands fa-whatsapp me-1"></i> ${cell}</a>` : '<span class="text-muted small italic">Sin teléfono</span>') 
                },
                { 
                    id: 'direccion',
                    name: "Dirección", 
                    formatter: (cell) => DataGrid.html(cell || '<span class="text-muted small italic">Sin dirección</span>') 
                },
                { 
                    id: 'tipo',
                    name: "Tipo", 
                    formatter: (cell) => {
                        const isEmpresa = cell === 'Empresa';
                        // Persona: Verde Esmeralda | Empresa: Morado Amatista
                        const config = isEmpresa 
                            ? { color: '#6366f1', bg: 'rgba(99, 102, 241, 0.1)', icon: 'fa-building', text: 'Empresa' }
                            : { color: '#10b981', bg: 'rgba(16, 185, 129, 0.1)', icon: 'fa-user', text: 'Persona' };
                        
                        return DataGrid.html(`
                            <span class="badge rounded-pill px-3 py-2 fw-bold text-uppercase" 
                                  style="font-size: 0.65rem; color: ${config.color}; background-color: ${config.bg}; border: 1px solid ${config.color}20;">
                                <i class="fa-solid ${config.icon} me-1"></i> ${cell || config.text}
                            </span>
                        `);
                    }
                },
                { 
                    id: 'notas',
                    name: "Notas", 
                    formatter: (cell) => DataGrid.html(`<span class="text-muted small italic">${cell || 'Sin notas'}</span>`) 
                },
                { 
                    id: 'acciones',
                    name: "Acciones",
                    sort: false,
                    formatter: (id) => {
                        if (!id) return '';
                        return DataGrid.html(`
                            <div class="btn-group">
                                <button class="btn btn-sm btn-light rounded-3 px-3 fw-bold text-primary" title="Cotizar"><i class="fa-solid fa-file-invoice-dollar"></i></button>
                                <a href="/eventos/clientes/${id}/edit" class="btn btn-sm btn-light rounded-3 mx-1 d-flex align-items-center" title="Editar"><i class="fa-solid fa-pen"></i></a>
                                <form action="/eventos/clientes/${id}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-light rounded-3 btn-delete-cliente" title="Eliminar"><i class="fa-solid fa-trash text-danger"></i></button>
                                </form>
                            </div>
                        `);
                    }
                }
            ]
        }).render();

        // Manejo de eliminación con SweetAlert2
        document.addEventListener('click', function(e) {
            if (e.target.closest('.btn-delete-cliente')) {
                const form = e.target.closest('.delete-form');
                window.Notify.confirm({
                    title: '¿Eliminar cliente?',
                    text: 'Esta acción no se puede deshacer.',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result) {
                        form.submit();
                    }
                });
            }
        });
    });
</script>
@endsection
