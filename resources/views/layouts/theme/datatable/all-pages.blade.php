<nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
    <ul class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                <span class="page-link" aria-hidden="true">&lsaquo;</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
            </li>
        @endif

        {{-- Always show the first page number --}}
        <li class="page-item {{ $paginator->currentPage() === 1 ? 'active' : '' }}">
            <a class="page-link" href="{{ $paginator->url(1) }}">1</a>
        </li>

        {{-- Page Links with Dots and Next Two Pages --}}
        @php
            $startPage = max(2, $paginator->currentPage() - 1);
            $endPage = min($paginator->lastPage(), $paginator->currentPage() + 2);
        @endphp

        @if ($paginator->currentPage() > 4)
            {{-- Show dots for hidden pages before the last page if applicable --}}
            <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
        @endif

        @for ($page = $startPage; $page <= $endPage; $page++)
            @if ($page == $paginator->currentPage())
                <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url($page) }}">{{ $page }}</a>
                </li>
            @endif
        @endfor

        @if ($paginator->currentPage() + 2 < $paginator->lastPage() && $paginator->lastPage() > 5 )
            {{-- Show dots for hidden pages after the last page if applicable --}}
            <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
        @endif

        {{-- Always show the last page number --}}
        
        @if($paginator->currentPage() != $paginator->lastPage() && $paginator->lastPage() - 2 > $paginator->currentPage() ) 
        <li class="page-item ">
            <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a>
        </li>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
            </li>
        @else
            <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                <span class="page-link" aria-hidden="true">&rsaquo;</span>
            </li>
        @endif
    </ul>
</nav>
