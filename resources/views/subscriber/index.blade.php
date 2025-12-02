<x-layouts.app>
    <x-slot name="header">
        <x-h2>
            {{ __('Email List') }} > {{ $emailList->title }} > {{ __('Subscribers') }}
        </x-h2>
    </x-slot>

    <x-card class="space-y-4">
        <div class="flex justify-between">
            <x-button.link :href="route('subscribers.create', $emailList)">
                {{ __('Add a new subscriber') }}
            </x-button.link>
            <x-form :action="route('subscribers.index', $emailList)" class="w-3/5 flex space-x-4" flat x-data x-ref="form">
                <x-input.checkbox
                    name="showTrash"
                    :label="__('Show deleted records')"
                    value="1"
                    @click="$refs.form.submit()"
                    :checked="$showTrash"
                />
                <x-input.text name="search" :placeholder="__('Search')" :value="$search" class="w-full"/>
            </x-form>
        </div>
        <x-table :headers="['#', __('Name'), __('Email'), __('Actions')]">
            <x-slot name="body">
                @foreach ($subscribers as $subscriber)
                    <tr>
                        <x-table.td class="w-1">{{ $subscriber->id }}</x-td>
                        <x-table.td>{{ $subscriber->name }}</x-td>
                        <x-table.td>{{ $subscriber->email }}</x-td>
                        <x-table.td class="w-1">
                            @unless ($subscriber->trashed())
                                <x-form
                                    :action="route('subscribers.destroy', [$emailList, $subscriber])"
                                    delete
                                    flat
                                >
                                    <x-button.secondary
                                        type="submit"
                                        onclick="return confirm('{{ __('Are you sure you want to delete this subscriber?') }}');"
                                    >
                                        {{ __('Delete') }}
                                    </x-button.secondary>
                                </x-form>
                            @else
                                <x-badge danger>
                                    {{  __('Deleted') }}
                                </x-badge>
                            @endunless
                        </x-table.td>
                    </tr>
                @endforeach
            </x-slot>
        </x-table>
        {{ $subscribers->links() }}
    </x-card>
</x-layouts.app>
