{{-- Guildhall (.gh-sheet) pagination — dark/gold theme, used by the spell catalog. --}}
@if ($paginator->hasPages())
    <nav class="gh-pagination" aria-label="Paginação de resultados">
        @if ($paginator->onFirstPage())
            <span class="gh-pagination-link gh-pagination-disabled" aria-disabled="true" aria-label="Página anterior">
                <x-gh-icon name="arrow-left"/>
            </span>
        @else
            <a class="gh-pagination-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Página anterior">
                <x-gh-icon name="arrow-left"/>
            </a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="gh-pagination-link gh-pagination-disabled">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="gh-pagination-link gh-pagination-active" aria-current="page">{{ $page }}</span>
                    @else
                        <a class="gh-pagination-link" href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a class="gh-pagination-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Próxima página">
                <x-gh-icon name="arrow-right"/>
            </a>
        @else
            <span class="gh-pagination-link gh-pagination-disabled" aria-disabled="true" aria-label="Próxima página">
                <x-gh-icon name="arrow-right"/>
            </span>
        @endif
    </nav>
@endif
