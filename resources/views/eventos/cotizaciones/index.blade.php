@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <h3 class="fw-bold mb-0">Gestión de Cotizaciones</h3>
        <button class="btn btn-primary rounded-3 px-4 shadow-sm fw-bold">
            <i class="fa-solid fa-plus me-2"></i> Nueva Cotización
        </button>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-muted small text-uppercase border-0">ID</th>
                            <th class="py-3 text-muted small text-uppercase border-0">Cliente</th>
                            <th class="py-3 text-muted small text-uppercase border-0">Fecha Evento</th>
                            <th class="py-3 text-muted small text-uppercase border-0">Total</th>
                            <th class="py-3 text-muted small text-uppercase border-0">Estado</th>
                            <th class="pe-4 py-3 text-muted small text-uppercase border-0 text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="ps-4 fw-bold text-primary">COT-001</td>
                            <td>
                                <div class="fw-bold">Juan Pérez</div>
                                <div class="extra-small text-muted">Fiesta Infantil</div>
                            </td>
                            <td>20 de Abril, 2026</td>
                            <td class="fw-bold">$350,000</td>
                            <td>
                                <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2">Pendiente</span>
                            </td>
                            <td class="pe-4 text-end">
                                <button class="btn btn-sm btn-success rounded-3 me-1" title="Enviar WhatsApp"><i class="fa-brands fa-whatsapp"></i></button>
                                <button class="btn btn-sm btn-danger rounded-3 me-1" title="Descargar PDF"><i class="fa-solid fa-file-pdf"></i></button>
                                <button class="btn btn-sm btn-light rounded-3" title="Editar"><i class="fa-solid fa-pen"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td class="ps-4 fw-bold text-primary">COT-002</td>
                            <td>
                                <div class="fw-bold">Empresa ABC</div>
                                <div class="extra-small text-muted">Lanzamiento Producto</div>
                            </td>
                            <td>25 de Abril, 2026</td>
                            <td class="fw-bold">$1,200,000</td>
                            <td>
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">Aceptada</span>
                            </td>
                            <td class="pe-4 text-end">
                                <button class="btn btn-sm btn-success rounded-3 me-1" title="Enviar WhatsApp"><i class="fa-brands fa-whatsapp"></i></button>
                                <button class="btn btn-sm btn-danger rounded-3 me-1" title="Descargar PDF"><i class="fa-solid fa-file-pdf"></i></button>
                                <button class="btn btn-sm btn-light rounded-3" title="Editar"><i class="fa-solid fa-pen"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
