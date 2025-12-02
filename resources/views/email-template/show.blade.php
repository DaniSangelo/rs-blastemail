<x-layouts.app>
    <x-slot name="header">
        <x-h2>
            {{ __('Email Template') }}
        </x-h2>
    </x-slot>

    <x-card class='space-y-4'>
        <div class="flex justify-between items-center">
            <div>
                <span class="opacity-70">{{ __('Name') }}:</span> {{ $emailTemplate->name }}
            </div>
            <x-button.link secondary :href="route('email-template.index')">
                {{ __('Back to list') }}
            </x-button.link>
        </div>
        {{-- Use !! allows to render html with all styles instead a plain text --}}
        <div class="p-20 border-2 border-gray-400 rounded flex justify-center">
            {!! $emailTemplate->body !!}
        </div>
    </x-card>
</x-layouts.app>
