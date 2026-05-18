@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="inline-flex">
        <span class="inline-flex rtl:flex-row-reverse shadow-sm rounded-md">
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" class="inline-flex items-center px-3 py-2 text-sm font-medium text-slate-400 bg-white border border-slate-300 cursor-not-allowed rounded-l-md">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-3 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-l-md hover:bg-slate-50">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="inline-flex items-center px-3.5 py-2 -ml-px text-sm font-medium text-slate-500 bg-white border border-slate-300">{{ $element }}</span>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="inline-flex items-center px-3.5 py-2 -ml-px text-sm font-semibold text-white bg-teal-500 border border-teal-500">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="inline-flex items-center px-3.5 py-2 -ml-px text-sm font-medium text-slate-700 bg-white border border-slate-300 hover:bg-slate-50">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-3 py-2 -ml-px text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-r-md hover:bg-slate-50">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                </a>
            @else
                <span class="inline-flex items-center px-3 py-2 -ml-px text-sm font-medium text-slate-400 bg-white border border-slate-300 cursor-not-allowed rounded-r-md">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                </span>
            @endif
        </span>
    </nav>
@endif
