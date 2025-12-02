<x-layouts.app>
    <x-slot name="header">
        <x-h2>
            {{ __('Email List') }}
        </x-h2>
    </x-slot>

    <x-card class="space-y-4">
        @unless ($emailLists->isEmpty() && blank($search))
            <div class="flex justify-between">
                <x-button.link :href="route('email-list.create')">
                    {{ __('Create New Email List') }}
                </x-button.link>
                <x-form :action="route('email-list.index')" class="w-2/5">
                    <x-input.text name="search" :placeholder="__('Search')" :value="$search"/>
                </x-form>
            </div>
            <x-table :headers="['#', __('Email list'), __('Qty Subscribers'), __('Actions')]">
                <x-slot name="body">
                    @foreach ($emailLists as $list)
                        <tr>
                            <x-table.td class="w-1">{{ $list->id }}</x-td>
                            <x-table.td>{{ $list->title }}</x-td>
                            <x-table.td class="w-1">{{ $list->subscribers_count }}</x-td>
                            <x-table.td>
                                <x-button.link secondary :href="route('subscribers.index', $list)">
                                    Subscribers
                                </x-button.link>
                            </x-td>
                        </tr>
                    @endforeach
                </x-slot>
            </x-table>
            {{ $emailLists->links() }}
        @else
            <div class="flex justify-center">
                <x-button.link :href="route('email-list.create')">
                    {{ __('Create New Email List') }}
                </x-button.link>
            </div>
        @endunless
    </x-card>
</x-layouts.app>
