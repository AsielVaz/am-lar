@php($canEditCompanyData = $canEditCompanyData ?? true)
<section class="form-section field-group-full">
    <div class="form-section-heading">
        <p class="panel-kicker">Identidad</p>
        <h3 class="section-title">Datos generales</h3>
    </div>

    <div class="section-grid">
        <div class="field-group {{ blank(old('nombre', $empresa->nombre)) ? 'is-missing' : '' }}">
            <label for="nombre">Nombre</label>
            <input id="nombre" type="text" name="nombre" value="{{ old('nombre', $empresa->nombre) }}" class="{{ blank(old('nombre', $empresa->nombre)) ? 'field-input-missing' : '' }}" @disabled(! $canEditCompanyData) required>
            @error('nombre')
                <small class="field-error">{{ $message }}</small>
            @enderror
        </div>

        <div class="field-group {{ blank(old('rfc', $empresa->rfc)) ? 'is-missing' : '' }}">
            <label for="rfc">RFC</label>
            <input id="rfc" type="text" name="rfc" value="{{ old('rfc', $empresa->rfc) }}" maxlength="13" class="{{ blank(old('rfc', $empresa->rfc)) ? 'field-input-missing' : '' }}" @disabled(! $canEditCompanyData) required>
            @error('rfc')
                <small class="field-error">{{ $message }}</small>
            @enderror
        </div>

        <div class="field-group field-group-full {{ blank(old('direccion', $empresa->direccion)) ? 'is-missing' : '' }}">
            <label for="direccion">Direccion</label>
            <textarea id="direccion" name="direccion" rows="4" class="{{ blank(old('direccion', $empresa->direccion)) ? 'field-input-missing' : '' }}" @disabled(! $canEditCompanyData) required>{{ old('direccion', $empresa->direccion) }}</textarea>
            @error('direccion')
                <small class="field-error">{{ $message }}</small>
            @enderror
        </div>

        <div class="field-group {{ blank(old('codigo_postal', $empresa->codigo_postal)) ? 'is-missing' : '' }}">
            <label for="codigo_postal">Codigo postal</label>
            <input id="codigo_postal" type="text" name="codigo_postal" value="{{ old('codigo_postal', $empresa->codigo_postal) }}" class="{{ blank(old('codigo_postal', $empresa->codigo_postal)) ? 'field-input-missing' : '' }}" @disabled(! $canEditCompanyData) required>
            @error('codigo_postal')
                <small class="field-error">{{ $message }}</small>
            @enderror
        </div>

        <div class="field-group">
            <label for="estatus">Estatus</label>
            <select id="estatus" name="estatus" @disabled(! $canEditCompanyData) required>
                <option value="activa" @selected(old('estatus', $empresa->estatus) === 'activa')>Activa</option>
                <option value="inactiva" @selected(old('estatus', $empresa->estatus) === 'inactiva')>Inactiva</option>
                <option value="inerte" @selected(old('estatus', $empresa->estatus) === 'inerte')>Inerte</option>
            </select>
            @error('estatus')
                <small class="field-error">{{ $message }}</small>
            @enderror
        </div>

        <div class="field-group">
            <label for="prioridad">Prioridad</label>
            <select id="prioridad" name="prioridad" @disabled(! $canEditCompanyData) required>
                <option value="alta" @selected(old('prioridad', $empresa->prioridad) === 'alta')>Alta</option>
                <option value="media" @selected(old('prioridad', $empresa->prioridad) === 'media')>Media</option>
                <option value="baja" @selected(old('prioridad', $empresa->prioridad) === 'baja')>Baja</option>
            </select>
            @error('prioridad')
                <small class="field-error">{{ $message }}</small>
            @enderror
        </div>

        <div class="field-group {{ blank(old('logo', $empresa->logo)) ? 'is-missing' : '' }}">
            <label for="logo">Logo</label>
            <input id="logo" type="file" name="logo" accept=".jpg,.jpeg,.png,.webp" class="{{ blank($empresa->logo) ? 'field-input-missing' : '' }}" @disabled(! $canEditCompanyData)>
            @error('logo')
                <small class="field-error">{{ $message }}</small>
            @enderror
        </div>

        @if ($empresa->logo)
            <div class="field-group field-group-full">
                <span class="detail-label">Logo actual</span>
                <img src="{{ asset('storage/' . $empresa->logo) }}" alt="Logo actual" class="logo-preview">
            </div>
        @endif
    </div>
</section>

<section class="form-section field-group-full">
    <div class="form-section-heading">
        <p class="panel-kicker">Contacto</p>
        <h3 class="section-title">Datos operativos y web</h3>
    </div>

    <div class="section-grid">
        <div class="field-group {{ blank(old('telefono', $empresa->telefono)) ? 'is-missing' : '' }}">
            <label for="telefono">Telefono</label>
            <input id="telefono" type="text" name="telefono" value="{{ old('telefono', $empresa->telefono) }}" class="{{ blank(old('telefono', $empresa->telefono)) ? 'field-input-missing' : '' }}" @disabled(! $canEditCompanyData)>
            @error('telefono')
                <small class="field-error">{{ $message }}</small>
            @enderror
        </div>

        <div class="field-group {{ blank(old('correo', $empresa->correo)) ? 'is-missing' : '' }}">
            <label for="correo">Correo</label>
            <input id="correo" type="email" name="correo" value="{{ old('correo', $empresa->correo) }}" class="{{ blank(old('correo', $empresa->correo)) ? 'field-input-missing' : '' }}" @disabled(! $canEditCompanyData)>
            @error('correo')
                <small class="field-error">{{ $message }}</small>
            @enderror
        </div>

        <div class="field-group {{ blank(old('sitio_web', $empresa->sitio_web)) ? 'is-missing' : '' }}">
            <label for="sitio_web">Sitio web</label>
            <input id="sitio_web" type="text" name="sitio_web" value="{{ old('sitio_web', $empresa->sitio_web) }}" class="{{ blank(old('sitio_web', $empresa->sitio_web)) ? 'field-input-missing' : '' }}" @disabled(! $canEditCompanyData)>
            @error('sitio_web')
                <small class="field-error">{{ $message }}</small>
            @enderror
        </div>

        <div class="field-group {{ blank(old('fin_dominio_web', $empresa->fin_dominio_web)) ? 'is-missing' : '' }}">
            <label for="fin_dominio_web">Fin del dominio web</label>
            <input id="fin_dominio_web" type="date" name="fin_dominio_web" value="{{ old('fin_dominio_web', $empresa->fin_dominio_web) }}" class="{{ blank(old('fin_dominio_web', $empresa->fin_dominio_web)) ? 'field-input-missing' : '' }}" @disabled(! $canEditCompanyData)>
            @error('fin_dominio_web')
                <small class="field-error">{{ $message }}</small>
            @enderror
        </div>

        <div class="field-group field-group-full {{ blank(old('contrasena_iofacturo', $empresa->contrasena_iofacturo)) ? 'is-missing' : '' }}">
            <label for="contrasena_iofacturo">Contrasena IOFacturo</label>
            <input id="contrasena_iofacturo" type="text" name="contrasena_iofacturo" value="{{ old('contrasena_iofacturo', $empresa->contrasena_iofacturo) }}" class="{{ blank(old('contrasena_iofacturo', $empresa->contrasena_iofacturo)) ? 'field-input-missing' : '' }}" @disabled(! $canEditCompanyData)>
            @error('contrasena_iofacturo')
                <small class="field-error">{{ $message }}</small>
            @enderror
        </div>
    </div>
</section>

@unless(isset($hidePrimaryActions) && $hidePrimaryActions)
    <div class="form-actions field-group-full">
        <a href="{{ route('empresas.index') }}" class="button button-secondary">Cancelar</a>
        <button type="submit" class="button button-primary">Guardar empresa</button>
    </div>
@endunless
