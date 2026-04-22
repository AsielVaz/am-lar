@if ($paginator->hasPages())
    <nav class="panel-pagination" role="navigation" aria-label="Paginacion">
        <div class="panel-pagination-summary">
            Mostrando {{ $paginator->firstItem() ?? 0 }} a {{ $paginator->lastItem() ?? 0 }} de {{ $paginator->total() }} registros
        </div>

        <div class="panel-pagination-links">
            @if ($paginator->onFirstPage())
                <span class="panel-page-button is-disabled">Anterior</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="panel-page-button panel-page-button-nav">Anterior</a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="panel-page-ellipsis">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="panel-page-number is-active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="panel-page-number">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="panel-page-button panel-page-button-nav">Siguiente</a>
            @else
                <span class="panel-page-button is-disabled">Siguiente</span>
            @endif
        </div>
    </nav>
@endif
