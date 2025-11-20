
<div class="row">
    <div class="col-md-2 col-lg-2">
        <div class="form-group" style="padding:5px;">
            <label for="nroplaca" class="form-label"><b>Nro Placa</b></label>
            <input type="text" name="nroplaca" class="form-control" maxlength="8" value="{{ old('nroplaca', $vehiculos->nroplaca ?? '') }}" {{ isset($vehiculos) ? 'readonly' : '' }}>
            @error('nroplaca') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4 col-lg-4">
        <div class="form-group" style="padding:5px;">
            <label for="marca" class="form-label"><b>Marca</b></label>
            <input type="text" name="marca" class="form-control" maxlength="20" value="{{ old('marca', $vehiculos->marca ?? '') }}">
            @error('marca') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-4 col-lg-4">
        <div class="form-group" style="padding:5px;">
            <label for="modelo" class="form-label"><b>Modelo</b></label>
            <input type="text" name="modelo" class="form-control" maxlength="20" value="{{ old('modelo', $vehiculos->modelo ?? '') }}">
            @error('modelo') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-4 col-lg-4">
        <div class="form-group" style="padding:5px;">
            <label for="color" class="form-label"><b>Color</b></label>
            <input type="text" name="color" class="form-control" maxlength="20" value="{{ old('color', $vehiculos->color ?? '') }}">
            @error('color') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-2 col-lg-2">
        <div class="form-group" style="padding:5px;">
            <label for="activo" class="form-label"><b>Activo</b></label>
            <select name="activo" class="form-select">
                <option value="S" {{ (old('activo', $vehiculos->activo ?? '') == 'S') ? 'selected' : '' }}>S&iacute;</option>
                <option value="N" {{ (old('activo', $vehiculos->activo ?? '') == 'N') ? 'selected' : '' }}>No</option>
            </select>
            @error('activo') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
</div>
<div class="row">


</div>

