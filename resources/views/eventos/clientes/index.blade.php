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
                c.nombre,
                c.telefono,
                c.direccion,
                c.tipo,
                c.notas || 'Sin notas',
                null
            ]),
            columns: [
                { name: "Cliente", formatter: (cell) => DataGrid.html(`<div class="d-flex align-items-center"><div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3"><i class="fa-solid fa-user text-primary"></i></div><div class="fw-bold">${cell}</div></div>`) },
                { name: "Teléfono", formatter: (cell) => DataGrid.html(`<a href="https://wa.me/${cell}" target="_blank" class="text-decoration-none text-success fw-bold"><i class="fa-brands fa-whatsapp me-1"></i> ${cell}</a>`) },
                { name: "Dirección" },
                { name: "Tipo", formatter: (cell) => DataGrid.html(`<span class="badge ${cell === 'Empresa' ? 'bg-purple bg-opacity-10 text-purple' : 'bg-blue bg-opacity-10 text-blue'} rounded-pill px-3 py-2">${cell}</span>`) },
                { name: "Notas", formatter: (cell) => DataGrid.html(`<span class="text-muted small italic">${cell}</span>`) },
                { 
                    name: "Acciones",
                    formatter: () => DataGrid.html(`
                        <div class="btn-group">
                            <button class="btn btn-sm btn-light rounded-3 px-3 fw-bold text-primary"><i class="fa-solid fa-file-invoice-dollar me-1"></i> Cotizar</button>
                            <button class="btn btn-sm btn-light rounded-3 mx-1"><i class="fa-solid fa-pen"></i></button>
                            <button class="btn btn-sm btn-light rounded-3"><i class="fa-solid fa-trash text-danger"></i></button>
                        </div>
                    `)
                }
            ]
        }).render();
    });
</script>
@endsection
