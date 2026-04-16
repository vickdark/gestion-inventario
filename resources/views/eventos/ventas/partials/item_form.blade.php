<div class="row g-3 align-items-end item-row mb-3 border-bottom pb-3">
    <div class="col-md-3">
        <label for="items_{{ $index }}_type" class="form-label">Tipo</label>
        <select class="form-select item-type" name="items[{{ $index }}][type]" id="items_{{ $index }}_type" required>
            <option value="">Seleccione</option>
            <option value="equipo" {{ old('items.'.$index.'.type', $item->itemable_type == 'App\\Models\\Equipo' ? 'equipo' : '') == 'equipo' ? 'selected' : '' }}>Equipo</option>
            <option value="paquete" {{ old('items.'.$index.'.type', $item->itemable_type == 'App\\Models\\Paquete' ? 'paquete' : '') == 'paquete' ? 'selected' : '' }}>Paquete</option>
        </select>
    </div>
    <div class="col-md-4">
        <label for="items_{{ $index }}_id" class="form-label">Item</label>
        <select class="form-select item-id" name="items[{{ $index }}][id]" id="items_{{ $index }}_id" required data-old-value="{{ old('items.'.$index.'.id', $item->itemable_id) }}">
            <option value="">Seleccione</option>
            @php
                $selectedType = old('items.'.$index.'.type', $item->itemable_type == 'App\\Models\\Equipo' ? 'equipo' : 'paquete');
                $itemsList = $selectedType == 'equipo' ? $equipos : $paquetes;
            @endphp
            @foreach($itemsList as $optionItem)
                <option value="{{ $optionItem->id }}" {{ old('items.'.$index.'.id', $item->itemable_id) == $optionItem->id ? 'selected' : '' }}>
                    {{ $optionItem->nombre }}
                </option>
            @endforeach
        </select>
        <input type="hidden" class="item-nombre" name="items[{{ $index }}][nombre]" value="{{ old('items.'.$index.'.nombre', $item->nombre) }}" data-old-value="{{ old('items.'.$index.'.nombre', $item->nombre) }}">
    </div>
    <div class="col-md-2">
        <label for="items_{{ $index }}_cantidad" class="form-label">Cantidad</label>
        <input type="number" class="form-control item-cantidad" name="items[{{ $index }}][cantidad]" id="items_{{ $index }}_cantidad" value="{{ old('items.'.$index.'.cantidad', $item->cantidad) }}" min="1" required data-old-value="{{ old('items.'.$index.'.cantidad', $item->cantidad) }}">
    </div>
    <div class="col-md-2">
        <label for="items_{{ $index }}_precio" class="form-label">Precio Unitario</label>
        <input type="number" class="form-control item-precio" name="items[{{ $index }}][precio]" id="items_{{ $index }}_precio" value="{{ old('items.'.$index.'.precio', $item->precio_unitario) }}" step="0.01" min="0" required data-old-value="{{ old('items.'.$index.'.precio', $item->precio_unitario) }}">
    </div>
    <div class="col-md-1">
        <button type="button" class="btn btn-danger remove-item-btn">
            <i class="fa-solid fa-trash"></i>
        </button>
    </div>
</div>