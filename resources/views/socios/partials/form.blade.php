<div class="form-section">
    <div class="form-section-heading">
        <p class="panel-kicker">Datos principales</p>
        <h3 class="section-title">Informacion de la P. Fisica</h3>
    </div>

    <div class="section-grid">
        <div class="field-group">
            <label for="estatus_visual">Estatus</label>
            <select id="estatus_visual" disabled>
                <option selected>Activa</option>
                <option>Inactiva</option>
                <option>Inerte</option>
            </select>
            <p class="helper-text">Etiqueta visual solicitada para P. Fisicas.</p>
        </div>

        <div class="field-group">
            <label for="puesto">Puesto</label>
            <select id="puesto" name="puesto" required>
                <option value="Reprecentante legal" @selected(old('puesto', $socio->puesto) === 'Reprecentante legal')>Reprecentante legal</option>
                <option value="Socio accionario" @selected(old('puesto', $socio->puesto) === 'Socio accionario')>Socio accionario</option>
            </select>
            @error('puesto')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="field-group">
            <label for="nombre">Nombre</label>
            <input id="nombre" name="nombre" type="text" value="{{ old('nombre', $socio->nombre) }}" required>
            @error('nombre')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="field-group field-group-full">
            <label for="direccion">Direccion</label>
            <input id="direccion" name="direccion" type="text" value="{{ old('direccion', $socio->direccion) }}" required>
            @error('direccion')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="field-group">
            <label for="rfc">RFC</label>
            <input id="rfc" name="rfc" type="text" maxlength="13" value="{{ old('rfc', $socio->rfc) }}" required>
            @error('rfc')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="field-group">
            <label for="contrasena">Contrasena</label>
            <input id="contrasena" name="contrasena" type="text" value="{{ old('contrasena', $socio->contrasena) }}">
            @error('contrasena')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

<div class="form-section">
    <div class="form-section-heading">
        <p class="panel-kicker">Documentacion</p>
        <h3 class="section-title">Archivos de la P. Fisica</h3>
    </div>

    <div class="section-grid">
        <div class="field-group">
            <label for="ine_pdf">INE (PDF)</label>
            <input id="ine_pdf" name="ine_pdf" type="file" accept=".pdf">
            @include('empresas.partials.document-link', ['archivo' => $socio->ine_pdf])
            @error('ine_pdf')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="field-group">
            <label for="csf_pdf">CSF (PDF)</label>
            <input id="csf_pdf" name="csf_pdf" type="file" accept=".pdf">
            @include('empresas.partials.document-link', ['archivo' => $socio->csf_pdf])
            @error('csf_pdf')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="field-group">
            <label for="certificado_cer">Certificado .cer</label>
            <input id="certificado_cer" name="certificado_cer" type="file" accept=".cer">
            @include('empresas.partials.document-link', ['archivo' => $socio->certificado_cer])
            @error('certificado_cer')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="field-group">
            <label for="llave_key">Llave .key</label>
            <input id="llave_key" name="llave_key" type="file" accept=".key">
            @include('empresas.partials.document-link', ['archivo' => $socio->llave_key])
            @error('llave_key')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

<div class="form-section">
    <div class="form-section-heading">
        <p class="panel-kicker">Asignacion</p>
        <h3 class="section-title">P. Morales relacionadas</h3>
    </div>

    <div class="section-grid">
        <div class="field-group field-group-full">
            <label for="empresa_ids">P. Morales</label>
            <select id="empresa_ids" name="empresa_ids[]" multiple size="8">
                @foreach ($empresasDisponibles as $empresa)
                    <option value="{{ $empresa->id }}" @selected(in_array($empresa->id, $selectedEmpresas))>{{ $empresa->nombre }}</option>
                @endforeach
            </select>
            <p class="helper-text">Puedes relacionar la P. Fisica con una o varias P. Morales.</p>
            @error('empresa_ids')
                <span class="field-error">{{ $message }}</span>
            @enderror
            @error('empresa_ids.*')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

<div class="form-actions form-actions-bottom">
    <a href="{{ route('socios.index') }}" class="button button-secondary">Cancelar</a>
    <button type="submit" class="button button-primary">{{ $isEdit ? 'Guardar cambios' : 'Crear P. Fisica' }}</button>
</div>
