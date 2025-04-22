
<div class="row">
    <div class="col-md-6 col-lg-6">
        <div class="form-group" style="padding:5px;">
            <label for="id_personal" class="form-label">Personal</label>
			  <select name="id_personal" class="form-select">
			          <option value="">-- Seleccione --</option>
			          @foreach($personal as $p)
			              <option value="{{ $p->id_personal }}" {{ old('id_personal', $usuarios->id_personal ?? '') == $p->id_personal ? 'selected' : '' }}>
			                  {{ $p->apellido_paterno }} {{ $p->apellido_materno }}, {{ $p->nombres }}
			              </option>
			          @endforeach
                          </select>
            @error('id_personal') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
    
</div>
<div class="row">
    <div class="col-md-4 col-lg-4">
        <div class="form-group" style="padding:5px;">
            <label for="apellido_paterno" class="form-label">Usuario</label>
            <input type="text" name="usuario" class="form-control" maxlength="20" value="{{ old('usuario', $usuarios->usuario ?? '') }}">
            @error('apellido_paterno') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-4 col-lg-4">
        <div class="form-group" style="padding:5px;">
            <label for="apellido_materno" class="form-label">Password</label>
            <input type="text" name="password" class="form-control" maxlength="20" value="{{ old('password', $usuarios->password ?? '') }}">
            @error('apellido_materno') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-4 col-lg-4">
        <div class="form-group" style="padding:5px;">
            <label for="activo" class="form-label">Activo</label>
            <select name="activo" class="form-select">
                <option value="S" {{ (old('activo', $usuarios->activo ?? '') == 'S') ? 'selected' : '' }}>S&iacute;</option>
                <option value="N" {{ (old('activo', $usuarios->activo ?? '') == 'N') ? 'selected' : '' }}>No</option>
            </select>
            @error('activo') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
</div>
