<div class="mb-3">
    <label>Country Code</label>
    <input type="text"
           name="country_code"
           class="form-control"
           value="{{ old('country_code', $country->country_code ?? '') }}">
</div>

<div class="mb-3">
    <label>Country Name</label>
    <input type="text"
           name="country_name"
           class="form-control"
           value="{{ old('country_name', $country->country_name ?? '') }}">
</div>

<div class="mb-3">
    <label>Currency</label>
    <input type="text"
           name="currency"
           class="form-control"
           value="{{ old('currency', $country->currency ?? '') }}">
</div>

<div class="mb-3">
    <label>Currency Code</label>
    <input type="text"
           name="currency_code"
           class="form-control"
           value="{{ old('currency_code', $country->currency_code ?? '') }}">
</div>

<div class="mb-3">
    <label>Capital</label>
    <input type="text"
           name="capital"
           class="form-control"
           value="{{ old('capital', $country->capital ?? '') }}">
</div>

<div class="mb-3">
    <label>Region</label>
    <input type="text"
           name="region"
           class="form-control"
           value="{{ old('region', $country->region ?? '') }}">
</div>

<div class="mb-3">
    <label>Subregion</label>
    <input type="text"
           name="subregion"
           class="form-control"
           value="{{ old('subregion', $country->subregion ?? '') }}">
</div>

<div class="mb-3">
    <label>Timezone</label>
    <input type="text"
           name="timezone"
           class="form-control"
           value="{{ old('timezone', $country->timezone ?? '') }}">
</div>

<div class="mb-3">
    <label>Language</label>
    <input type="text"
           name="language"
           class="form-control"
           value="{{ old('language', $country->language ?? '') }}">
</div>

<div class="mb-3">
    <label>Latitude</label>
    <input type="number"
           step="0.000001"
           name="latitude"
           class="form-control"
           value="{{ old('latitude', $country->latitude ?? '') }}">
</div>

<div class="mb-3">
    <label>Longitude</label>
    <input type="number"
           step="0.000001"
           name="longitude"
           class="form-control"
           value="{{ old('longitude', $country->longitude ?? '') }}">
</div>