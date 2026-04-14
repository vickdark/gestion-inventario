@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <h3 class="fw-bold mb-0">Agenda de Eventos</h3>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary rounded-3 px-3 shadow-sm fw-bold">
                <i class="fa-solid fa-calendar-day me-2"></i> Hoy
            </button>
            <button class="btn btn-primary rounded-3 px-4 shadow-sm fw-bold">
                <i class="fa-solid fa-plus me-2"></i> Nuevo Evento
            </button>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-0 p-4 border-bottom">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-sm btn-light rounded-circle p-2 shadow-sm"><i class="fa-solid fa-chevron-left"></i></button>
                    <h5 class="mb-0 fw-bold">Abril 2026</h5>
                    <button class="btn btn-sm btn-light rounded-circle p-2 shadow-sm"><i class="fa-solid fa-chevron-right"></i></button>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-light border rounded-pill px-4 fw-bold shadow-sm">Mes</button>
                    <button class="btn btn-sm btn-primary rounded-pill px-4 fw-bold shadow-sm">Semana</button>
                    <button class="btn btn-sm btn-light border rounded-pill px-4 fw-bold shadow-sm">Día</button>
                </div>
            </div>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-bordered text-center align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 text-muted small text-uppercase fw-bold border-0">Dom</th>
                            <th class="py-3 text-muted small text-uppercase fw-bold border-0">Lun</th>
                            <th class="py-3 text-muted small text-uppercase fw-bold border-0">Mar</th>
                            <th class="py-3 text-muted small text-uppercase fw-bold border-0">Mié</th>
                            <th class="py-3 text-muted small text-uppercase fw-bold border-0">Jue</th>
                            <th class="py-3 text-muted small text-uppercase fw-bold border-0">Vie</th>
                            <th class="py-3 text-muted small text-uppercase fw-bold border-0">Sáb</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="p-4 border-light">29</td>
                            <td class="p-4 border-light">30</td>
                            <td class="p-4 border-light">31</td>
                            <td class="p-4 border-light bg-light fw-bold text-primary">1</td>
                            <td class="p-4 border-light">2</td>
                            <td class="p-4 border-light">3</td>
                            <td class="p-4 border-light">
                                <div class="mb-2">4</div>
                                <div class="badge bg-warning bg-opacity-10 text-warning w-100 rounded-pill py-2 border border-warning border-opacity-25 small shadow-sm">
                                    <i class="fa-solid fa-circle me-1" style="font-size: 0.5rem;"></i> Boda Civil
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-4 border-light">5</td>
                            <td class="p-4 border-light">6</td>
                            <td class="p-4 border-light">7</td>
                            <td class="p-4 border-light">8</td>
                            <td class="p-4 border-light">9</td>
                            <td class="p-4 border-light">10</td>
                            <td class="p-4 border-light">11</td>
                        </tr>
                        <tr>
                            <td class="p-4 border-light">12</td>
                            <td class="p-4 border-light">13</td>
                            <td class="p-4 border-light">
                                <div class="mb-2">14</div>
                                <div class="badge bg-info bg-opacity-10 text-info w-100 rounded-pill py-2 border border-info border-opacity-25 small shadow-sm">
                                    <i class="fa-solid fa-circle me-1" style="font-size: 0.5rem;"></i> Cumpleaños 1
                                </div>
                                <div class="badge bg-success bg-opacity-10 text-success w-100 rounded-pill py-2 border border-success border-opacity-25 small mt-1 shadow-sm">
                                    <i class="fa-solid fa-circle me-1" style="font-size: 0.5rem;"></i> Bautizo VIP
                                </div>
                            </td>
                            <td class="p-4 border-light">15</td>
                            <td class="p-4 border-light">16</td>
                            <td class="p-4 border-light">17</td>
                            <td class="p-4 border-light">18</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
