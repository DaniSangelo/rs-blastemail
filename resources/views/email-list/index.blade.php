<x-layouts.app>
    <x-slot name="header">
        <x-h2>
            {{ __('Email List') }}
        </x-h2>
    </x-slot>

    <x-card>
        @unless ($emailLists->isEmpty())
            <x-table :headers="['#', __('Email list'), __('Qty'), __('Actions')]">
                <x-slot name="body">
                    @foreach ($emailLists as $list)
                        <tr>
                            <x-table.td>{{ $list->id }}</x-td>
                            <x-table.td>{{ $list->title }}</x-td>
                            <x-table.td>{{ $list->subscribers->count() }}</x-td>
                            <x-table.td>Silver</x-td>
                        </tr>
                    @endforeach
                </x-slot>
            </x-table>
        @else
            <div class="flex justify-center">
                <x-link-button :href="route('email-list.create')">
                    {{ __('Create New Email List') }}
                </x-link-button>
            </div>
        @endunless
    </x-card>
</x-layouts.app>
