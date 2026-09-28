@if ($paginator->hasPages())
    <nav class="pagination" role="navigation" aria-label="Pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="disabled" aria-disabled="true">&laquo;</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous">&laquo;</a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="disabled" aria-disabled="true">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next">&raquo;</a>
        @else
            <span class="disabled" aria-disabled="true">&raquo;</span>
        @endif
    </nav>
@endif