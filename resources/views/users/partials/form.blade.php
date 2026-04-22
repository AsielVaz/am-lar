<div class="form-section">
    <div class="form-section-heading">
        <p class="panel-kicker">Datos principales</p>
        <h3 class="section-title">Usuario del sistema</h3>
    </div>

    <div class="section-grid">
        <div class="field-group">
            <label for="name">Nombre</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required>
            @error('name')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="field-group">
            <label for="email">Correo electronico</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required>
            @error('email')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="field-group">
            <label for="role">Rol</label>
            <select id="role" name="role" required>
                @foreach ($roles as $roleValue => $roleLabel)
                    <option value="{{ $roleValue }}" @selected(old('role', $user->role) === $roleValue)>{{ $roleLabel }}</option>
                @endforeach
            </select>
            @error('role')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="field-group {{ $isEdit ? '' : 'field-group-full' }}">
            <label for="password">{{ $isEdit ? 'Nueva contrasena' : 'Contrasena' }}</label>
            <input id="password" name="password" type="password" {{ $isEdit ? '' : 'required' }}>
            <p class="helper-text">
                {{ $isEdit ? 'Deja este campo vacio para conservar la contrasena actual.' : 'Usa una contrasena segura de al menos 8 caracteres.' }}
            </p>
            @error('password')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

<div class="form-actions form-actions-bottom">
    <a href="{{ route('users.index') }}" class="button button-secondary">Cancelar</a>
    <button type="submit" class="button button-primary">{{ $isEdit ? 'Guardar cambios' : 'Crear usuario' }}</button>
</div>
