@csrf

<div class="row">

    <div class="col-md-6 mb-3">

        <label class="form-label fw-semibold">
            Shipment Code
        </label>

        <input
            type="text"
            name="shipment_code"
            class="form-control @error('shipment_code') is-invalid @enderror"
            value="{{ old('shipment_code', $shipment->shipment_code ?? '') }}"
            placeholder="Contoh : SH001">

        @error('shipment_code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

    </div>

    <div class="col-md-6 mb-3">

        <label class="form-label fw-semibold">
            Supplier
        </label>

        <input
            type="text"
            name="supplier"
            class="form-control"
            value="{{ old('supplier', $shipment->supplier ?? '') }}"
            placeholder="Alibaba Group">

    </div>

</div>

<div class="row">

    <div class="col-md-6 mb-3">

        <label class="form-label fw-semibold">
            Origin Country
        </label>

        <input
            type="text"
            name="origin_country"
            class="form-control"
            value="{{ old('origin_country', $shipment->origin_country ?? '') }}">

    </div>

    <div class="col-md-6 mb-3">

        <label class="form-label fw-semibold">
            Destination Country
        </label>

        <input
            type="text"
            name="destination_country"
            class="form-control"
            value="{{ old('destination_country', $shipment->destination_country ?? '') }}">

    </div>

</div>

<div class="row">

    <div class="col-md-6 mb-3">

        <label class="form-label fw-semibold">
            Origin Port
        </label>

        <input
            type="text"
            name="origin_port"
            class="form-control"
            value="{{ old('origin_port', $shipment->origin_port ?? '') }}">

    </div>

    <div class="col-md-6 mb-3">

        <label class="form-label fw-semibold">
            Destination Port
        </label>

        <input
            type="text"
            name="destination_port"
            class="form-control"
            value="{{ old('destination_port', $shipment->destination_port ?? '') }}">

    </div>

</div>

<div class="row">

    <div class="col-md-6 mb-3">

        <label class="form-label fw-semibold">
            Vessel Name
        </label>

        <input
            type="text"
            name="vessel_name"
            class="form-control"
            value="{{ old('vessel_name', $shipment->vessel_name ?? '') }}">

    </div>

    <div class="col-md-3 mb-3">

        <label class="form-label fw-semibold">
            Departure Date
        </label>

        <input
            type="date"
            name="departure_date"
            class="form-control"
            value="{{ old('departure_date', $shipment->departure_date ?? '') }}">

    </div>

    <div class="col-md-3 mb-3">

        <label class="form-label fw-semibold">
            Estimated Arrival
        </label>

        <input
            type="date"
            name="estimated_arrival"
            class="form-control"
            value="{{ old('estimated_arrival', $shipment->estimated_arrival ?? '') }}">

    </div>

</div>

<div class="row">

    <div class="col-md-6 mb-3">

        <label class="form-label fw-semibold">
            Status
        </label>

        <select
            name="status"
            class="form-select">

            <option value="">Choose Status</option>

            <option value="Pending"
                {{ old('status',$shipment->status ?? '')=='Pending'?'selected':'' }}>
                Pending
            </option>

            <option value="On Process"
                {{ old('status',$shipment->status ?? '')=='On Process'?'selected':'' }}>
                On Process
            </option>

            <option value="On Shipping"
                {{ old('status',$shipment->status ?? '')=='On Shipping'?'selected':'' }}>
                On Shipping
            </option>

            <option value="Arrived"
                {{ old('status',$shipment->status ?? '')=='Arrived'?'selected':'' }}>
                Arrived
            </option>

            <option value="Delayed"
                {{ old('status',$shipment->status ?? '')=='Delayed'?'selected':'' }}>
                Delayed
            </option>

        </select>

    </div>

    <div class="col-md-6 mb-3">

        <label class="form-label fw-semibold">
            Risk Level
        </label>

        <select
            name="risk_level"
            class="form-select">

            <option value="">Choose Risk</option>

            <option value="Low"
                {{ old('risk_level',$shipment->risk_level ?? '')=='Low'?'selected':'' }}>
                Low
            </option>

            <option value="Medium"
                {{ old('risk_level',$shipment->risk_level ?? '')=='Medium'?'selected':'' }}>
                Medium
            </option>

            <option value="High"
                {{ old('risk_level',$shipment->risk_level ?? '')=='High'?'selected':'' }}>
                High
            </option>

        </select>

    </div>

</div>

<div class="row">

    <div class="col-md-6 mb-3">

        <label class="form-label fw-semibold">
            Latitude
        </label>

        <input
            type="text"
            name="latitude"
            class="form-control"
            value="{{ old('latitude',$shipment->latitude ?? '') }}">

    </div>

    <div class="col-md-6 mb-3">

        <label class="form-label fw-semibold">
            Longitude
        </label>

        <input
            type="text"
            name="longitude"
            class="form-control"
            value="{{ old('longitude',$shipment->longitude ?? '') }}">

    </div>

</div>

<div class="mb-4">

    <label class="form-label fw-semibold">
        Description
    </label>

    <textarea
        name="description"
        rows="4"
        class="form-control">{{ old('description',$shipment->description ?? '') }}</textarea>

</div>

<div class="d-flex justify-content-end">

    <a href="{{ route('shipments.index') }}"
       class="btn btn-secondary me-2">

        Cancel

    </a>

    <button class="btn btn-pink">

        <i class="bi bi-check-circle me-2"></i>

        Save Shipment

    </button>

</div>