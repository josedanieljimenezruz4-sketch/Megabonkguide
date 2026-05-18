@if ($paginator->hasPages())
    <nav class="pagination-neon-container" aria-label="Pagination">
        <ul class="pagination-neon">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="Anterior">
                    <span class="page-link">&laquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Anterior">&laquo;</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled page-item-number" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active page-item-number" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item page-item-number"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Siguiente">&raquo;</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="Siguiente">
                    <span class="page-link">&raquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif

<style>
.pagination-neon-container {
    display: flex !important;
    flex-direction: row !important;
    justify-content: center !important;
    margin-top: 2rem !important;
    margin-bottom: 2rem !important;
}
.pagination-neon {
    display: flex !important;
    flex-direction: row !important;
    list-style: none !important;
    padding: 0 !important;
    gap: 0.5rem !important;
}
.pagination-neon .page-item .page-link {
    display: block !important;
    padding: 0.5rem 1rem !important;
    background-color: #1e1e2e !important;
    color: #fff !important;
    border: 2px solid #00f3ff !important;
    border-radius: 4px !important;
    text-decoration: none !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 0 5px rgba(0, 243, 255, 0.2) !important;
    font-weight: 800 !important;
}
.pagination-neon .page-item .page-link:hover {
    background-color: #00f3ff !important;
    color: #000 !important;
    box-shadow: 0 0 10px rgba(0, 243, 255, 0.8) !important;
}
.pagination-neon .page-item.active .page-link {
    background-color: #00f3ff !important;
    color: #000 !important;
    border-color: #00f3ff !important;
    box-shadow: 0 0 15px rgba(0, 243, 255, 0.8) !important;
}
.pagination-neon .page-item.disabled .page-link {
    color: #666 !important;
    border-color: #333 !important;
    background-color: #111 !important;
    box-shadow: none !important;
    cursor: not-allowed !important;
}
</style>
