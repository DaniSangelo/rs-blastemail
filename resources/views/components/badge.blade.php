@props([
    'danger' => null,
    'warning' => null,
])

<span {{ $attributes->class([
    'rounded-lg w-fit border px-2 py-1 text-xs font-medium text-white',
    'border-red-500 bg-red-500  dark:border-red-500 dark:bg-red-500 dark:text-white' => $danger,
    'border-amber-500 bg-amber-500  dark:border-amber-500 dark:bg-amber-500 dark:text-white' => $warning,
]) }} >
    {{ $slot }}
</span>