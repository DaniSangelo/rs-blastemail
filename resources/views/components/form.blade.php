@props([
    'post' => null,
    'put' => null,
    'delete' => null,
    'patch' => null,
])

@php
    $httpMethod = $post || $put || $delete || $patch ? 'post' : 'get';
@endphp

<form method="{{ $httpMethod }}" {{ $attributes->class(['gap-4 flex flex-col']) }}>
    @if(!$httpMethod != 'GET')
        @csrf
    @endif

    @if ($put)
        @method('put')
    @endif

    @if ($delete)
        @method('delete')
    @endif

    @if ($patch)
        @method('patch')
    @endif

    {{  $slot }}
</form>
