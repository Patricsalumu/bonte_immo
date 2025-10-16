@if ($paginator->hasPages())
<nav role="navigation" aria-label="Pagination Navigation" class="d-flex justify-content-center">
    <style>
        /* Styles responsives pour la pagination personnalisée */
        .pagination-custom { display: inline-flex; flex-wrap: wrap; gap: .25rem; font-size: .9rem; list-style: none; padding: 0; margin: 0; }
        .pagination-custom .page-item { margin: 0; }
        .pagination-custom .page-link { display: inline-block; padding: .375rem .625rem; border: 1px solid #dee2e6; background: #fff; color: #333; border-radius: .25rem; }
        .pagination-custom .page-item.active .page-link { background: #0d6efd; color: #fff; border-color: #0d6efd; }
        .pagination-custom .page-item.disabled .page-link { color: #6c757d; background: #fff; border-color: #dee2e6; cursor: default; }
        @media (max-width: 576px) {
            .pagination-custom { overflow-x: auto; -webkit-overflow-scrolling: touch; white-space: nowrap; }
            .pagination-custom .page-item { display: inline-block; }
        }
    </style>
    <ul class="pagination pagination-custom" style="align-items:center;">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled" aria-disabled="true" aria-label="Précédent">
                <span class="page-link">Précédent</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Précédent">Précédent</a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Suivant">Suivant</a>
            </li>
        @else
            <li class="page-item disabled" aria-disabled="true" aria-label="Suivant">
                <span class="page-link">Suivant</span>
            </li>
        @endif
    </ul>
</nav>
@endif
