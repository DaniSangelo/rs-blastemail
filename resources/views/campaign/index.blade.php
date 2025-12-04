<x-layouts.app>
    <x-slot name="header">
        <x-h2>
            {{ __('Campaigns') }}
        </x-h2>
    </x-slot>

    <x-card class="space-y-4">
        <div class="flex justify-between">
            <x-button.link :href="route('campaign.create')">
                {{ __('Add a new campaign') }}
            </x-button.link>
            <x-form :action="route('campaign.index')" class="w-3/5 flex space-x-4" flat x-data x-ref="form">
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
        <x-table :headers="['#', __('Name'), __('Actions')]">
            <x-slot name="body">
                @foreach ($campaigns as $campaign)
                    <tr>
                        <x-table.td class="w-1"> {{ $campaign->id }}</x-table.td>
                        <x-table.td>
                            <a class="hover:underline" href="{{ route('campaign.show', $campaign) }}">{{ $campaign->name }}</a>
                        </x-table.td>
                        <x-table.td class="w-1">
                            <div class="flex space-x-4 items-center">
                                @unless ($campaign->trashed())
                                    <x-form
                                        :action="route('campaign.destroy', $campaign)"
                                        delete
                                        flat
                                    >
                                        <x-button.secondary
                                            type="submit"
                                            onclick="return confirm('{{ __('Are you sure you want to delete?') }}');"
                                        >
                                            {{ __('Delete') }}
                                        </x-button.secondary>
                                    </x-form>
                                @else
                                    <x-form
                                        :action="route('campaign.restore', $campaign)"
                                        patch
                                        flat
                                    >
                                        <x-button.secondary
                                            type="submit"
                                            danger
                                        >
                                            {{ __('Restore') }}
                                        </x-button.secondary>
                                    </x-form>
                                @endunless
                            </div>
                        </x-table.td>
                    </tr>
                @endforeach
            </x-slot>
        </x-table>
        {{ $campaigns->links() }}
    </x-card>
</x-layouts.app>
