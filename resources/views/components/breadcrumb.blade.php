@props(['links' => [], 'title'])

<div class="mb-8">
    <p class="text-gray-500">
        @foreach ($links as $link)
            @if ($loop->last)
                {{ $link['name'] }}
            @else
                <a href="{{ $link['url'] }}" class="text-primary">{{ $link['name'] }}</a> <span class="mx-1">/</span>
            @endif
        @endforeach
    </p>
    <h2 class="text-xl font-semibold mt-8">
        {{ $title }}
    </h2>
</div>
