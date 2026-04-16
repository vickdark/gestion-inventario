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
        <div class="card-body p-4">
            <div id="calendar"></div>
        </div>
    </div>
</div>

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            buttonText: {
                today: 'Hoy',
                month: 'Mes',
                week: 'Semana',
                day: 'Día'
            },
            events: @json($eventos),
            eventClick: function(info) {
                if (info.event.url) {
                    window.location.href = info.event.url;
                    info.jsEvent.preventDefault();
                }
            },
            themeSystem: 'bootstrap5'
        });
        calendar.render();
    });
</script>
<style>
    .fc .fc-button-primary {
        background-color: var(--bs-primary);
        border-color: var(--bs-primary);
    }
    .fc .fc-button-primary:hover {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    .fc .fc-toolbar-title {
        font-weight: 700;
        color: #334155;
    }
    .fc-event {
        cursor: pointer;
        padding: 2px 5px;
        border-radius: 4px;
        border: none;
    }
</style>
@endpush
</div>
@endsection
