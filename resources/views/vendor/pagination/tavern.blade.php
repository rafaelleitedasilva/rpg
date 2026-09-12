@if ($paginator->hasPages())
    <nav class="tavern-pagination" aria-label="Paginação de magias">
        @if ($paginator->onFirstPage())
            <span class="disabled" aria-disabled="true" aria-label="Página anterior">&lsaquo;</span>
        @else
            <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Página anterior">&lsaquo;</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="disabled">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="active" aria-current="page">{{ $page }}</span>
                    @else
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Próxima página">&rsaquo;</a>
        @else
            <span class="disabled" aria-disabled="true" aria-label="Próxima página">&rsaquo;</span>
        @endif
    </nav>
@endif
