@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <h3 class="fw-bold mb-0">Agenda de Eventos</h3>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div id="calendar" style="min-height: 600px;"></div>
        </div>
    </div>
</div>

<!-- Modal de Detalle de Evento -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Detalle del Evento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="d-flex align-items-center mb-4">
                    <div id="modalIcon" class="p-3 rounded-4 me-3 bg-opacity-10">
                        <i class="fa-solid fa-calendar-check fs-3"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold" id="modalFactura">#000</h4>
                        <span class="badge rounded-pill px-3 py-2" id="modalLogisticaBadge">Logística: -</span>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="p-3 bg-light rounded-4">
                            <small class="text-muted d-block mb-1">Monto Total</small>
                            <span class="fw-bold fs-5" id="modalMonto">$0</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded-4">
                            <small class="text-muted d-block mb-1">Saldo Pendiente</small>
                            <span class="fw-bold fs-5 text-danger" id="modalSaldo">$0</span>
                        </div>
                    </div>
                </div>

                <div class="list-group list-group-flush border-top border-bottom mb-4">
                    <div class="list-group-item px-0 py-2 border-0">
                        <small class="text-muted d-block">Cliente</small>
                        <span class="fw-bold fs-6" id="modalCliente">-</span>
                    </div>
                    <div class="list-group-item px-0 py-2 border-0">
                        <small class="text-muted d-block">Teléfono / Ubicación</small>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold" id="modalTelefono">-</span>
                            <span class="text-secondary small" id="modalDireccion">-</span>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-12">
                         <div class="p-3 bg-light rounded-4">
                            <h6 class="fw-bold mb-2 d-flex align-items-center small text-uppercase text-muted">
                                <i class="fa-solid fa-truck-fast me-2"></i> Logística y Transporte
                            </h6>
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted d-block">Vehículo / Carro</small>
                                    <span class="fw-bold" id="modalVehiculo">-</span>
                                </div>
                                <div class="col-6 border-start">
                                    <small class="text-muted d-block">Responsable / Personal</small>
                                    <span class="fw-bold text-truncate d-block" id="modalPersonal">-</span>
                                </div>
                            </div>
                            <div class="mt-2 pt-2 border-top">
                                <small class="text-muted d-block">Instrucciones / Notas</small>
                                <p class="mb-0 small fst-italic text-secondary" id="modalNotasLogistica">-</p>
                            </div>
                            <div id="modalLinkContainer" class="mt-2 d-none">
                                <a href="#" id="modalLinkMap" target="_blank" class="btn btn-sm btn-outline-danger w-100 rounded-pill">
                                    <i class="fa-solid fa-map-location-dot me-1"></i> Ver en Google Maps
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-0">
                    <h6 class="fw-bold mb-3 d-flex align-items-center">
                        <i class="fa-solid fa-boxes-stacked me-2 text-primary"></i> Equipos y Servicios
                    </h6>
                    <div class="table-responsive rounded-3 border">
                        <table class="table table-sm table-hover mb-0 align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-3 py-2 border-0 small">Item</th>
                                    <th class="text-center py-2 border-0 small">Cant</th>
                                    <th class="text-end pe-3 py-2 border-0 small">Precio</th>
                                </tr>
                            </thead>
                            <tbody id="modalItemsTableBody">
                                <!-- Se llena dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light rounded-3 fw-bold w-100" data-bs-dismiss="modal">Cerrar Detalle</button>
            </div>
        </div>
    </div>
</div>

<button id="btnShowModal" type="button" class="btn d-none" data-bs-toggle="modal" data-bs-target="#eventModal"></button>

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        
        function getHeaderToolbar() {
            if (window.innerWidth < 768) {
                return {
                    left: 'prev,next',
                    center: 'title',
                    right: 'today,listWeek'
                };
            }
            return {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            };
        }

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: window.innerWidth < 768 ? 'listWeek' : 'dayGridMonth',
            locale: 'es',
            height: 'auto',
            handleWindowResize: true,
            windowResizeDelay: 100,
            stickyHeaderDates: true,
            headerToolbar: getHeaderToolbar(),
            buttonText: {
                today: 'Hoy',
                month: 'Mes',
                week: 'Semana',
                list: 'Lista'
            },
            events: @json($eventos),
            eventClick: function(info) {
                info.jsEvent.preventDefault();
                const props = info.event.extendedProps;
                if (!props) return;

                const color = info.event.backgroundColor || '#3b82f6';

                // Llenar datos
                document.getElementById('modalFactura').innerText = props.factura || 'N/A';
                document.getElementById('modalCliente').innerText = props.cliente || '-';
                document.getElementById('modalTelefono').innerText = props.telefono || '-';
                document.getElementById('modalDireccion').innerText = props.direccion || '-';
                document.getElementById('modalLogisticaBadge').innerText = 'Logística: ' + (props.logistica || '-');
                document.getElementById('modalMonto').innerText = props.monto || '$0';
                document.getElementById('modalSaldo').innerText = props.saldo || '$0';
                document.getElementById('modalVehiculo').innerText = props.vehiculo || 'No asignado';
                document.getElementById('modalPersonal').innerText = props.personal || 'No asignado';
                document.getElementById('modalNotasLogistica').innerText = props.notas_logistica || 'Sin notas';
                
                const mapContainer = document.getElementById('modalLinkContainer');
                if (props.ubicacion_link) {
                    document.getElementById('modalLinkMap').href = props.ubicacion_link;
                    mapContainer.classList.remove('d-none');
                } else {
                    mapContainer.classList.add('d-none');
                }

                // Items
                const tableBody = document.getElementById('modalItemsTableBody');
                tableBody.innerHTML = '';
                if (props.items && props.items.length > 0) {
                    props.items.forEach(item => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td class="ps-2 py-2 small fw-medium text-wrap" style="max-width: 150px;">${item.nombre}</td>
                            <td class="text-center py-2 small">${item.cantidad}</td>
                            <td class="text-end pe-2 py-2 small text-muted text-nowrap">${item.precio}</td>
                        `;
                        tableBody.appendChild(tr);
                    });
                } else {
                    tableBody.innerHTML = '<tr><td colspan="3" class="text-center py-3 text-muted">Sin equipos registrados</td></tr>';
                }

                const badge = document.getElementById('modalLogisticaBadge');
                badge.style.backgroundColor = color + '20';
                badge.style.color = color;
                const iconBox = document.getElementById('modalIcon');
                iconBox.style.backgroundColor = color + '15';
                iconBox.style.color = color;

                document.getElementById('btnShowModal').click();
            },
            windowResize: function(view) {
                calendar.setOption('headerToolbar', getHeaderToolbar());
                if (window.innerWidth < 768) {
                    calendar.changeView('listWeek');
                } else {
                    calendar.changeView('dayGridMonth');
                }
            },
            themeSystem: 'bootstrap5'
        });
        calendar.render();
    });
</script>
<style>
    .fc { font-size: 0.9rem; }
    .fc .fc-toolbar-title {
        font-size: 1.1rem !important;
        font-weight: 800;
        color: #1e293b;
    }
    .fc .fc-button {
        padding: 0.4rem 0.7rem !important;
        font-size: 0.85rem !important;
        border-radius: 8px !important;
    }
    .fc .fc-button-primary {
        background-color: #f8fafc;
        border-color: #e2e8f0;
        color: #64748b;
    }
    .fc .fc-button-active {
        background-color: #3b82f6 !important;
        border-color: #3b82f6 !important;
        color: white !important;
    }
    .fc-event {
        border-radius: 6px !important;
        padding: 2px 4px !important;
        margin-bottom: 2px !important;
    }
    
    @media (max-width: 767.98px) {
        .fc .fc-toolbar {
            display: grid;
            grid-template-areas: "title title" "left right";
            gap: 10px;
        }
        .fc .fc-toolbar-title { grid-area: title; text-align: center; }
        .fc .fc-toolbar-chunk:nth-child(1) { grid-area: left; }
        .fc .fc-toolbar-chunk:nth-child(3) { grid-area: right; text-align: right; }
        
        .modal-dialog { margin: 10px; }
        .p-4 { padding: 1.25rem !important; }
    }

    #modalIcon i { color: inherit; }
    .table-sm td, .table-sm th { font-size: 0.75rem; }
</style>
@endpush
</div>
@endsection
