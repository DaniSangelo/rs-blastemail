<x-layouts.app>
    <x-slot name="header">
        <x-h2>
            {{ __('Email List') }}
        </x-h2>
    </x-slot>

    <x-card class="space-y-4">
        @unless ($emailLists->isEmpty() && blank($search))
            <div class="flex justify-between">
                <x-link-button :href="route('email-list.create')">
                    {{ __('Create New Email List') }}
                </x-link-button>
                <x-form :action="route('email-list.index')" class="w-2/5">
                    <x-text-input name="search" :placeholder="__('Search')" :value="$search"/>
                </x-form>
            </div>
            <x-table :headers="['#', __('Email list'), __('Qty'), __('Actions')]">
                <x-slot name="body">
                    @foreach ($emailLists as $list)
                        <tr>
                            <x-table.td>{{ $list->id }}</x-td>
                            <x-table.td>{{ $list->title }}</x-td>
                            <x-table.td>{{ $list->subscribers_count }}</x-td>
                            <x-table.td>Silver</x-td>
                        </tr>
                    @endforeach
                </x-slot>
            </x-table>
            {{ $emailLists->links() }}
        @else
            <div class="flex justify-center">
                <x-link-button :href="route('email-list.create')">
                    {{ __('Create New Email List') }}
                </x-link-button>
            </div>
        @endunless
    </x-card>
</x-layouts.app>
