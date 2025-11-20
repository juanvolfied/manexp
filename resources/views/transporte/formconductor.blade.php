
<div class="row">
    <div class="col-md-2 col-lg-2">
        <div class="form-group" style="padding:5px;">
            <label for="id_conductor" class="form-label"><b>ID Personal</b></label>
            <input type="text" name="id_conductor" class="form-control" maxlength="8" value="{{ old('id_conductor', $conductores->id_conductor ?? '') }}" {{ isset($conductores) ? 'readonly' : '' }}>
            @error('id_conductor') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4 col-lg-4">
        <div class="form-group" style="padding:5px;">
            <label for="apellido_paterno" class="form-label"><b>Apellido Paterno</b></label>
            <input type="text" name="apellido_paterno" class="form-control" maxlength="30" value="{{ old('apellido_paterno', $conductores->apellido_paterno ?? '') }}">
            @error('apellido_paterno') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-4 col-lg-4">
        <div class="form-group" style="padding:5px;">
            <label for="apellido_materno" class="form-label"><b>Apellido Materno</b></label>
            <input type="text" name="apellido_materno" class="form-control" maxlength="30" value="{{ old('apellido_materno', $conductores->apellido_materno ?? '') }}">
            @error('apellido_materno') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-4 col-lg-4">
        <div class="form-group" style="padding:5px;">
            <label for="nombres" class="form-label"><b>Nombres</b></label>
            <input type="text" name="nombres" class="form-control" maxlength="30" value="{{ old('nombres', $conductores->nombres ?? '') }}">
            @error('nombres') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3 col-lg-3">
        <div class="form-group" style="padding:5px;">
            <label for="nrolicencia" class="form-label"><b>Nro Licencia</b></label>
            <input type="text" name="nrolicencia" class="form-control" maxlength="15" value="{{ old('nrolicencia', $conductores->nrolicencia ?? '') }}">
            @error('nrolicencia') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-2 col-lg-2">
        <div class="form-group" style="padding:5px;">
            <label for="categoria" class="form-label"><b>Categoria</b></label>
            <input type="text" name="categoria" class="form-control" maxlength="5" value="{{ old('categoria', $conductores->categoria ?? '') }}">
            @error('categoria') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-2 col-lg-2">
        <div class="form-group" style="padding:5px;">
            <label for="activo" class="form-label"><b>Activo</b></label>
            <select name="activo" class="form-select">
                <option value="S" {{ (old('activo', $conductores->activo ?? '') == 'S') ? 'selected' : '' }}>S&iacute;</option>
                <option value="N" {{ (old('activo', $conductores->activo ?? '') == 'N') ? 'selected' : '' }}>No</option>
            </select>
            @error('activo') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
</div>
<div class="row">


</div>

