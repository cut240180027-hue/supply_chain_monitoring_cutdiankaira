<div class="mb-3">
    <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #4b5563;">Nama Pelabuhan</label>
    <input type="text"
           name="port_name"
           class="form-control"
           style="border-radius: 8px; font-size: 0.85rem; border: 1.5px solid #e5e7eb;"
           value="{{ old('port_name', $port->port_name ?? '') }}"
           required>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #4b5563;">Negara</label>
    <select name="country_id" 
            class="form-select" 
            style="border-radius: 8px; font-size: 0.85rem; border: 1.5px solid #e5e7eb;"
            required>
        <option value="">-- Pilih Negara --</option>
        @foreach($countries as $c)
            <option value="{{ $c->id }}" {{ old('country_id', $port->country_id ?? '') == $c->id ? 'selected' : '' }}>
                {{ $c->country_name }} ({{ $c->country_code }})
            </option>
        @endforeach
    </select>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #4b5563;">Latitude</label>
        <input type="number"
               step="0.000001"
               name="latitude"
               class="form-control"
               style="border-radius: 8px; font-size: 0.85rem; border: 1.5px solid #e5e7eb;"
               value="{{ old('latitude', $port->latitude ?? '') }}"
               required>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #4b5563;">Longitude</label>
        <input type="number"
               step="0.000001"
               name="longitude"
               class="form-control"
               style="border-radius: 8px; font-size: 0.85rem; border: 1.5px solid #e5e7eb;"
               value="{{ old('longitude', $port->longitude ?? '') }}"
               required>
    </div>
</div>
