<select {{ $attributes->class([
    'w-full appearance-none rounded-md border border-outline bg-surface-alt px-4 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-slate-900 dark:focus-visible:outline-primary-dark'
    ]) }}
>
    {{ $slot }}
</select>