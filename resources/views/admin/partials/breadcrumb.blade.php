<nav class="mb-6">
    <ol class="flex items-center gap-2 text-sm">
        <li>
            <a href="{{ route('admin.dashboard') }}" class="font-medium text-gray-500 hover:text-gray-700">
                Панель керування
            </a>
        </li>

        @if(isset($breadcrumbs) && count($breadcrumbs) > 0)
            @foreach($breadcrumbs as $breadcrumb)
                <li class="flex items-center gap-2">
                    <svg
                        class="fill-gray-400"
                        width="6"
                        height="10"
                        viewBox="0 0 6 10"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M1.375 9.71948L0.645996 9.05115L4.10933 5.58781L0.645996 2.12448L1.375 1.45615L5.5665 5.64765L1.375 9.71948Z"
                            fill=""
                        />
                    </svg>

                    @if(isset($breadcrumb['url']))
                        <a href="{{ $breadcrumb['url'] }}" class="font-medium text-gray-500 hover:text-gray-700">
                            {{ $breadcrumb['title'] }}
                        </a>
                    @else
                        <span class="font-medium text-gray-900">
                            {{ $breadcrumb['title'] }}
                        </span>
                    @endif
                </li>
            @endforeach
        @endif
    </ol>
</nav>
