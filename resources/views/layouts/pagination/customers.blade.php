<nav class="d-flex justify-content-center">
    <ul class="pagination">
        <li class="page-item">
            <a class="page-link" href="{{ route('customer.index', ['type' => $type, 'page' => 1]) }}" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        @for ($i = 1; $i <= $total; $i++)
            <li class="page-item {{ $page == $i ? 'active' : '' }}"><a class="page-link"
                    href="{{ route('customer.index', ['type' => $type, 'page' => $i]) }}">{{ $i }}</a></li>
        @endfor
        <li class="page-item">
            <a class="page-link" href="{{ route('customer.index', ['type' => $type, 'page' => $total]) }}" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>
