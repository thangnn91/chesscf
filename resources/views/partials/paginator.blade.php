@if ($paginator->lastPage() > 1)
<ul>
    <li><a href="{{ $paginator->url(1) }}" class="pagination-prev"><i class="icon-left-4"></i> <span>PREV page</span></a></li>
    @for ($i = 1; $i <= $paginator->lastPage(); $i++)
        <li class="{{ ($paginator->currentPage() == $i) ? ' active' : '' }}">
            <a href="{{ $paginator->url($i) }}"><span>{{ $i }}</span></a>
        </li>
        @endfor
        <li><a href="{{ $paginator->url($paginator->currentPage()+1) }}" class="pagination-next"><span>next page</span> <i class="icon-right-4"></i></a></li>
</ul>
@endif