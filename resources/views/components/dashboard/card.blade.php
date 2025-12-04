@props([
    'heading',
    'subheading'
])

<div class="border-2 border-slate-700 p-8 bg-slate-900 text-center rounded-xl">
    <div class="font-bold text-5xl font-mono">{{ $heading }}</div>
    <div class="text-xl mt-1 opacity-50">{{ $subheading }}</div>
</div>