@php
    if ($totalPages != 0) {
        $remainder = $totalPages > 10 ? $totalPages % 10 : 0;
        $init = $remainder != 0 ? 10 * $remainder : 1;
    } else {
        $init = 1;
        $totalPages = 1;
    }
@endphp
<nav class="col-auto">
    <ul class="pagination">
        <li class="page-item @if ($page == 1) disabled @endif">

            <a class="page-link"
                href="{{ $page != 1 ? route('quality', ['status' => $status, 'page' => $page - 1]) : '#' }}"
                aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        @for ($i = $init; $i <= $totalPages; $i++)
            <li class="page-item @if ($page == $i) active @endif "><a class="page-link"
                    href="{{ Route('quality', ['status' => $status, 'page' => $i]) }}">{{ $i }}</a>
            </li>
        @endfor
        <li class="page-item">
            <a class="page-link @if ($page == $totalPages) disabled @endif"
                href="{{ $page != $totalPages ? route('quality', ['status' => $status, 'page' => $page + 1]) : '#' }}"
                aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>
