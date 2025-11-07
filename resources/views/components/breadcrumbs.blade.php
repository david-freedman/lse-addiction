@props(['items' => []])

<div class="breadcrumbs">
    <div class="breadcrumbs__container">
        <ul>
            @foreach($items as $item)
                <li>
                    @if(isset($item['url']))
                        <a href="{{ $item['url'] }}">{{ $item['title'] }}</a>
                    @else
                        <span>{{ $item['title'] }}</span>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</div>
