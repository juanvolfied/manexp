
<div class="row">
    <div class="col-md-6 col-lg-6">
        <div class="form-group" style="padding:5px;">
            <label for="id_usuario" class="form-label">Nombre del personal >>> Usuario</label>
			  <select name="id_usuario" class="form-select">
			          <option value="">-- Seleccione --</option>
			          @foreach($usuarios as $p)
			              <option value="{{ $p->id_usuario }}" {{ old('id_usuario', $perfilusuario->id_usuario ?? '') == $p->id_usuario ? 'selected' : '' }}>
			                  {{ $p->apellido_paterno }} {{ $p->apellido_materno }}, {{ $p->nombres }} >>> {{ $p->usuario }}
			              </option>
			          @endforeach
                          </select>
            @error('id_usuario') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-lg-6">
        <div class="form-group" style="padding:5px;">
            <label for="id_perfil" class="form-label">Perfiles</label>
			  <select name="id_perfil" class="form-select">
			          <option value="">-- Seleccione --</option>
			          @foreach($perfiles as $p)
			              <option value="{{ $p->id_perfil}}" {{ old('id_perfil', $perfilusuario->id_perfil ?? '') == $p->id_perfil ? 'selected' : '' }}>
			                  {{ $p->descri_perfil }}
			              </option>
			          @endforeach
                          </select>
            @error('id_perfil') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
</div>
<input type="hidden" id="activo" name="activo" value="S">
