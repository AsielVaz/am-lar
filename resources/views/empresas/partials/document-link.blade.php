@if ($archivo)
    <a href="{{ asset('storage/' . $archivo) }}" target="_blank" class="document-link">Ver archivo</a>
@else
    <span class="document-empty missing-glass-text">No cargado</span>
@endif
