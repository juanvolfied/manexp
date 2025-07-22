
<div class="row">
    <div class="col-md-6 col-lg-6">
        <div class="form-group" style="padding:5px;">
            <label for="descripciondep" class="form-label">Descripci&oacute;n</label>
            <input type="text" name="descripciondep" class="form-control" maxlength="35" value="{{ old('descripciondep', $deppoli->descripciondep ?? '') }}">
            @error('descripciondep') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
</div>