@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex space-x-1">

        {{-- Previous Page --}}
        @if ($paginator->onFirstPage())
            <span class="px-3 py-2 rounded bg-gray-200 text-gray-400 cursor-not-allowed">
                &lt;
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
               class="px-3 py-2 rounded bg-white border hover:bg-gray-100 text-gray-700">
                &lt;
            </a>
        @endif

        {{-- Page Links --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-3 py-2 rounded bg-white border text-gray-500">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-3 py-2 rounded bg-blue-600 text-white font-semibold shadow">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}"
                           class="px-3 py-2 rounded bg-white border hover:bg-gray-100 text-gray-700">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
               class="px-3 py-2 rounded bg-white border hover:bg-gray-100 text-gray-700">
                &gt;
            </a>
        @else
            <span class="px-3 py-2 rounded bg-gray-200 text-gray-400 cursor-not-allowed">
                &gt;
            </span>
        @endif

    </nav>
@endif
