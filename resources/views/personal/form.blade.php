
<div class="row">
    <div class="col-md-3 col-lg-3">
        <div class="form-group" style="padding:5px;">
            <label for="id_personal" class="form-label"><b>ID Personal</b></label>
            <input type="text" name="id_personal" class="form-control" maxlength="8" value="{{ old('id_personal', $personal->id_personal ?? '') }}" {{ isset($personal) ? 'readonly' : '' }}>
            @error('id_personal') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-lg-6">
        <div class="form-group" style="padding:5px;">
            <label for="apellido_paterno" class="form-label"><b>Apellido Paterno</b></label>
            <input type="text" name="apellido_paterno" class="form-control" maxlength="30" value="{{ old('apellido_paterno', $personal->apellido_paterno ?? '') }}">
            @error('apellido_paterno') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-6 col-lg-6">
        <div class="form-group" style="padding:5px;">
            <label for="apellido_materno" class="form-label"><b>Apellido Materno</b></label>
            <input type="text" name="apellido_materno" class="form-control" maxlength="30" value="{{ old('apellido_materno', $personal->apellido_materno ?? '') }}">
            @error('apellido_materno') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-lg-6">
        <div class="form-group" style="padding:5px;">
            <label for="nombres" class="form-label"><b>Nombres</b></label>
            <input type="text" name="nombres" class="form-control" maxlength="30" value="{{ old('nombres', $personal->nombres ?? '') }}">
            @error('nombres') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-6 col-lg-6">
        <div class="form-group" style="padding:5px;">
            <label for="activo" class="form-label"><b>Activo</b></label>
            <select name="activo" class="form-select">
                <option value="S" {{ (old('activo', $personal->activo ?? '') == 'S') ? 'selected' : '' }}>S&iacute;</option>
                <option value="N" {{ (old('activo', $personal->activo ?? '') == 'N') ? 'selected' : '' }}>No</option>
            </select>
            @error('activo') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
</div>
<div class="row">

                      <div class="col-md-6 col-lg-6">
                        <div class="form-group" style="padding:5px;">
                          <label for="dependencia"><b>Dependencia:</b></label>

<select name="id_dependencia" id="id_dependencia" class="">
    <option value=""></option>
    @foreach ($dependencias as $datos)
        <option value="{{ $datos->id_dependencia }}"
            {{ old('id_dependencia', $personal->id_dependencia ?? '') == $datos->id_dependencia ? 'selected' : '' }}>
            {{ $datos->descripcion }}
        </option>
    @endforeach
</select>           

                        </div>
                      </div>
                      <div class="col-md-6 col-lg-6">
                        <div class="form-group" style="padding:5px;">
                          <label for="despacho"><b>Despacho:</b></label>
<select name="despacho" id="despacho" class="form-select">
    <option value=""></option>
    @foreach(config('listas.listadespachos') as $valor => $texto)
        <option value="{{ $valor }}" 
            {{ (string) old('despacho', $personal->despacho ?? '') === (string) $valor ? 'selected' : '' }}>
            {{ $texto }}
        </option>
    @endforeach
</select>
                        </div>
                      </div>
</div>
<div class="row">
    <div class="col-md-6 col-lg-6">
        <div class="form-group" style="padding:5px;">
            <label for="tipocargo" class="form-label"><b>Tipo de Cargo</b></label>
            <select name="tipocargo" class="form-select">
                <option value="F" {{ (old('tipocargo', $personal->fiscal_asistente ?? '') == 'F') ? 'selected' : '' }}>Fiscal</option>
                <option value="A" {{ (old('tipocargo', $personal->fiscal_asistente ?? '') == 'A') ? 'selected' : '' }}>Asistente</option>
                <option value="C" {{ (old('tipocargo', $personal->fiscal_asistente ?? '') == 'C') ? 'selected' : '' }}>Asistente Coordinador por Dependencia</option>
                <option value="" {{ (old('tipocargo', $personal->fiscal_asistente ?? '') == '') ? 'selected' : '' }}>Otro tipo de Cargo</option>
            </select>
            @error('tipocargo') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-6 col-lg-6">
        <div class="form-group" style="padding:5px;">
            <label for="cargo" class="form-label"><b>Detalle Cargo</b></label>
            <input type="text" name="cargo" class="form-control" maxlength="50" value="{{ old('cargo', $personal->cargo ?? '') }}">
            @error('cargo') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

@section('scripts')
<script>

    $('#id_dependencia').selectize();

</script>
@endsection