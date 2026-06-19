@if ($paginator->hasPages())
    <nav class="saas-pagination-wrapper" aria-label="Pagination">
        <ul class="saas-pagination-list">
            
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="pagination-link prev-next disabled">
                        <i class="fa-solid fa-chevron-left"></i>
                        <span>{{ __('frontend.previous') }}</span>
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="pagination-link prev-next" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                        <i class="fa-solid fa-chevron-left"></i>
                        <span>{{ __('frontend.previous') }}</span>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="pagination-dots">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="pagination-link number">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="pagination-link number" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="pagination-link prev-next" href="{{ $paginator->nextPageUrl() }}" rel="next">
                        <span>{{ __('frontend.next') }}</span>
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="pagination-link prev-next disabled">
                        <span>{{ __('frontend.next') }}</span>
                        <i class="fa-solid fa-chevron-right"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif