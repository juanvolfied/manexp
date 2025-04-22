
<div class="row">
    <div class="col-md-3 col-lg-3">
        <div class="form-group" style="padding:5px;">
            <label for="id_personal" class="form-label">ID Personal</label>
            <input type="text" name="id_personal" class="form-control" maxlength="8" value="{{ old('id_personal', $personal->id_personal ?? '') }}" {{ isset($personal) ? 'readonly' : '' }}>
            @error('id_personal') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-lg-6">
        <div class="form-group" style="padding:5px;">
            <label for="apellido_paterno" class="form-label">Apellido Paterno</label>
            <input type="text" name="apellido_paterno" class="form-control" maxlength="30" value="{{ old('apellido_paterno', $personal->apellido_paterno ?? '') }}">
            @error('apellido_paterno') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-6 col-lg-6">
        <div class="form-group" style="padding:5px;">
            <label for="apellido_materno" class="form-label">Apellido Materno</label>
            <input type="text" name="apellido_materno" class="form-control" maxlength="30" value="{{ old('apellido_materno', $personal->apellido_materno ?? '') }}">
            @error('apellido_materno') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-lg-6">
        <div class="form-group" style="padding:5px;">
            <label for="nombres" class="form-label">Nombres</label>
            <input type="text" name="nombres" class="form-control" maxlength="30" value="{{ old('nombres', $personal->nombres ?? '') }}">
            @error('nombres') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-6 col-lg-6">
        <div class="form-group" style="padding:5px;">
            <label for="activo" class="form-label">Activo</label>
            <select name="activo" class="form-select">
                <option value="S" {{ (old('activo', $personal->activo ?? '') == 'S') ? 'selected' : '' }}>S&iacute;</option>
                <option value="N" {{ (old('activo', $personal->activo ?? '') == 'N') ? 'selected' : '' }}>No</option>
            </select>
            @error('activo') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
</div>
