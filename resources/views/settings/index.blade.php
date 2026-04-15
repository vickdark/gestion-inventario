@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h1 class="h3 mb-0 text-gray-800">Configuración del Sistema</h1>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-soft rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @foreach($settings as $group => $groupSettings)
                            <h5 class="mb-3 text-primary border-bottom pb-2">
                                <i class="fas {{ $group == 'general' ? 'fa-cog' : 'fa-building' }} me-2"></i>
                                {{ $group == 'general' ? 'Configuración General' : 'Datos de la Empresa' }}
                            </h5>
                            <div class="row g-3 mb-5">
                                @foreach($groupSettings as $setting)
                                    <div class="{{ $setting->type == 'textarea' ? 'col-12' : 'col-md-6' }}">
                                        <label for="{{ $setting->key }}" class="form-label fw-bold small text-muted text-uppercase">{{ $setting->display_name }}</label>
                                        @if($setting->type == 'text')
                                            <input type="text" name="{{ $setting->key }}" id="{{ $setting->key }}" class="form-control" value="{{ $setting->value }}">
                                        @elseif($setting->type == 'textarea')
                                            <textarea name="{{ $setting->key }}" id="{{ $setting->key }}" class="form-control" rows="3">{{ $setting->value }}</textarea>
                                        @elseif($setting->type == 'file')
                                            <div class="d-flex align-items-center gap-3">
                                                @if($setting->value)
                                                    <div class="bg-light p-2 rounded border" style="width: 80px; height: 80px;">
                                                        <img src="{{ asset('storage/' . $setting->value) }}" alt="Logo" class="img-fluid w-100 h-100 object-fit-contain">
                                                    </div>
                                                @endif
                                                <input type="file" name="{{ $setting->key }}" id="{{ $setting->key }}" class="form-control" accept="image/*">
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endforeach

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary rounded-pill px-4">
                                <i class="fas fa-save me-2"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-soft rounded-4 overflow-hidden h-100">
                <div class="card-body p-4">
                    <h5 class="mb-3">Información</h5>
                    <p class="text-muted small">
                        Desde este módulo puedes personalizar los textos principales del sistema, como el nombre de la aplicación y los títulos de la barra lateral.
                    </p>
                    <hr>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary-light p-2 rounded-3 me-3">
                            <i class="fas fa-info-circle text-primary"></i>
                        </div>
                        <div>
                            <span class="d-block fw-bold small">Personalización</span>
                            <span class="text-muted small">Los cambios se aplican globalmente.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
