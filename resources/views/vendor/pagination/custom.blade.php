@if ($paginator->hasPages())
    <div class="pagination">
        {{-- Кнопка "Назад" --}}
        @if ($paginator->onFirstPage())
            <button class="disabled" disabled>← Назад</button>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev">← Назад</a>
        @endif

        {{-- Элементы пагинации --}}
        @php
            $window = 2; // сколько страниц показывать слева и справа от текущей
            $start = max(1, $paginator->currentPage() - $window);
            $end = min($paginator->lastPage(), $paginator->currentPage() + $window);
        @endphp

        {{-- Первая страница и троеточие, если нужно --}}
        @if($start > 1)
            <a href="{{ $paginator->url(1) }}">1</a>
            @if($start > 2)
                <span class="pagination-ellipsis">...</span>
            @endif
        @endif

        {{-- Диапазон страниц --}}
        @for ($page = $start; $page <= $end; $page++)
            @if ($page == $paginator->currentPage())
                <button class="active" aria-current="page">{{ $page }}</button>
            @else
                <a href="{{ $paginator->url($page) }}">{{ $page }}</a>
            @endif
        @endfor

        {{-- Последняя страница и троеточие --}}
        @if($end < $paginator->lastPage())
            @if($end < $paginator->lastPage() - 1)
                <span class="pagination-ellipsis">...</span>
            @endif
            <a href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a>
        @endif

        {{-- Кнопка "Вперёд" --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next">Вперёд →</a>
        @else
            <button class="disabled" disabled>Вперёд →</button>
        @endif
    </div>
@endif