@php($documentos = $empresa->documentos)

<section class="form-section field-group-full">
    <div class="form-section-heading">
        <p class="panel-kicker">Legales</p>
        <h3 class="section-title">Documentos corporativos</h3>
    </div>

    <div class="section-grid">
        <div class="field-group {{ blank($documentos?->acta_constitutiva_pdf) ? 'is-missing' : '' }}">
            <label for="acta_constitutiva_pdf">Acta constitutiva (PDF)</label>
            <input id="acta_constitutiva_pdf" type="file" name="acta_constitutiva_pdf" accept=".pdf" class="{{ blank($documentos?->acta_constitutiva_pdf) ? 'field-input-missing' : '' }}">
            @error('acta_constitutiva_pdf')
                <small class="field-error">{{ $message }}</small>
            @enderror
            @include('empresas.partials.document-link', ['archivo' => $documentos?->acta_constitutiva_pdf])
        </div>

        <div class="field-group {{ blank($documentos?->asamblea_pdf) ? 'is-missing' : '' }}">
            <label for="asamblea_pdf">Asamblea (PDF)</label>
            <input id="asamblea_pdf" type="file" name="asamblea_pdf" accept=".pdf" class="{{ blank($documentos?->asamblea_pdf) ? 'field-input-missing' : '' }}">
            @error('asamblea_pdf')
                <small class="field-error">{{ $message }}</small>
            @enderror
            @include('empresas.partials.document-link', ['archivo' => $documentos?->asamblea_pdf])
        </div>

        <div class="field-group {{ blank($documentos?->registro_publico_pdf) ? 'is-missing' : '' }}">
            <label for="registro_publico_pdf">Registro publico de la propiedad y comercio (PDF)</label>
            <input id="registro_publico_pdf" type="file" name="registro_publico_pdf" accept=".pdf" class="{{ blank($documentos?->registro_publico_pdf) ? 'field-input-missing' : '' }}">
            @error('registro_publico_pdf')
                <small class="field-error">{{ $message }}</small>
            @enderror
            @include('empresas.partials.document-link', ['archivo' => $documentos?->registro_publico_pdf])
        </div>

        <div class="field-group {{ blank($documentos?->d32_pdf) ? 'is-missing' : '' }}">
            <label for="d32_pdf">32D (PDF)</label>
            <input id="d32_pdf" type="file" name="d32_pdf" accept=".pdf" class="{{ blank($documentos?->d32_pdf) ? 'field-input-missing' : '' }}">
            @error('d32_pdf')
                <small class="field-error">{{ $message }}</small>
            @enderror
            @include('empresas.partials.document-link', ['archivo' => $documentos?->d32_pdf])
        </div>

        <div class="field-group {{ blank($documentos?->d32_caducidad) ? 'is-missing' : '' }}">
            <label>Caducidad de 32D</label>
            <input type="text" value="{{ $documentos?->d32_caducidad?->format('Y-m-d') ?: 'Se calcula al subir el archivo' }}" class="{{ blank($documentos?->d32_caducidad) ? 'field-input-missing' : '' }}" readonly>
        </div>

        <div class="field-group field-group-full {{ blank($documentos?->comprobante_domicilio_pdf) ? 'is-missing' : '' }}">
            <label for="comprobante_domicilio_pdf">Comprobante de domicilio (PDF)</label>
            <input id="comprobante_domicilio_pdf" type="file" name="comprobante_domicilio_pdf" accept=".pdf" class="{{ blank($documentos?->comprobante_domicilio_pdf) ? 'field-input-missing' : '' }}">
            @error('comprobante_domicilio_pdf')
                <small class="field-error">{{ $message }}</small>
            @enderror
            @include('empresas.partials.document-link', ['archivo' => $documentos?->comprobante_domicilio_pdf])
        </div>

        <div class="field-group field-group-full {{ blank($documentos?->comprobante_domicilio_caducidad) ? 'is-missing' : '' }}">
            <label>Caducidad de comprobante de domicilio</label>
            <input type="text" value="{{ $documentos?->comprobante_domicilio_caducidad?->format('Y-m-d') ?: 'Se calcula al subir el archivo' }}" class="{{ blank($documentos?->comprobante_domicilio_caducidad) ? 'field-input-missing' : '' }}" readonly>
        </div>
    </div>
</section>

<section class="form-section field-group-full">
    <div class="form-section-heading">
        <p class="panel-kicker">SAT</p>
        <h3 class="section-title">Sellos y certificados</h3>
    </div>

    <div class="section-grid">
        <div class="field-group {{ blank($documentos?->sello_sat_key) ? 'is-missing' : '' }}">
            <label for="sello_sat_key">Sello SAT .key</label>
            <input id="sello_sat_key" type="file" name="sello_sat_key" accept=".key" class="{{ blank($documentos?->sello_sat_key) ? 'field-input-missing' : '' }}">
            @error('sello_sat_key')
                <small class="field-error">{{ $message }}</small>
            @enderror
            @include('empresas.partials.document-link', ['archivo' => $documentos?->sello_sat_key])
        </div>

        <div class="field-group {{ blank(old('sello_sat_key_contrasena', $documentos?->sello_sat_key_contrasena)) ? 'is-missing' : '' }}">
            <label for="sello_sat_key_contrasena">Contrasena de Sello SAT .key</label>
            <input id="sello_sat_key_contrasena" type="text" name="sello_sat_key_contrasena" value="{{ old('sello_sat_key_contrasena', $documentos?->sello_sat_key_contrasena) }}" class="{{ blank(old('sello_sat_key_contrasena', $documentos?->sello_sat_key_contrasena)) ? 'field-input-missing' : '' }}">
            @error('sello_sat_key_contrasena')
                <small class="field-error">{{ $message }}</small>
            @enderror
        </div>

        <div class="field-group {{ blank($documentos?->sello_sat_cer) ? 'is-missing' : '' }}">
            <label for="sello_sat_cer">Sello SAT .cer</label>
            <input id="sello_sat_cer" type="file" name="sello_sat_cer" accept=".cer" class="{{ blank($documentos?->sello_sat_cer) ? 'field-input-missing' : '' }}">
            @error('sello_sat_cer')
                <small class="field-error">{{ $message }}</small>
            @enderror
            @include('empresas.partials.document-link', ['archivo' => $documentos?->sello_sat_cer])
        </div>
    </div>
</section>

<section class="form-section field-group-full">
    <div class="form-section-heading">
        <p class="panel-kicker">FIEL</p>
        <h3 class="section-title">Firma electronica</h3>
    </div>

    <div class="section-grid">
        <div class="field-group {{ blank($documentos?->fiel_key) ? 'is-missing' : '' }}">
            <label for="fiel_key">FIEL .key</label>
            <input id="fiel_key" type="file" name="fiel_key" accept=".key" class="{{ blank($documentos?->fiel_key) ? 'field-input-missing' : '' }}">
            @error('fiel_key')
                <small class="field-error">{{ $message }}</small>
            @enderror
            @include('empresas.partials.document-link', ['archivo' => $documentos?->fiel_key])
        </div>

        <div class="field-group {{ blank(old('fiel_key_contrasena', $documentos?->fiel_key_contrasena)) ? 'is-missing' : '' }}">
            <label for="fiel_key_contrasena">Contrasena de FIEL .key</label>
            <input id="fiel_key_contrasena" type="text" name="fiel_key_contrasena" value="{{ old('fiel_key_contrasena', $documentos?->fiel_key_contrasena) }}" class="{{ blank(old('fiel_key_contrasena', $documentos?->fiel_key_contrasena)) ? 'field-input-missing' : '' }}">
            @error('fiel_key_contrasena')
                <small class="field-error">{{ $message }}</small>
            @enderror
        </div>

        <div class="field-group {{ blank($documentos?->fiel_cer) ? 'is-missing' : '' }}">
            <label for="fiel_cer">FIEL .cer</label>
            <input id="fiel_cer" type="file" name="fiel_cer" accept=".cer" class="{{ blank($documentos?->fiel_cer) ? 'field-input-missing' : '' }}">
            @error('fiel_cer')
                <small class="field-error">{{ $message }}</small>
            @enderror
            @include('empresas.partials.document-link', ['archivo' => $documentos?->fiel_cer])
        </div>
    </div>
</section>
