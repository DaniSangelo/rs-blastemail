<x-layouts.app>
    <x-slot name="header">
        <x-h2>
            {{ __('Templates') }}
        </x-h2>
    </x-slot>

    <x-card class="space-y-4">
        <div class="flex justify-between">
            <x-button.link :href="route('email-template.create')">
                {{ __('Add a new template') }}
            </x-button.link>
            <x-form :action="route('email-template.index')" class="w-3/5 flex space-x-4" flat x-data x-ref="form">
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
                @foreach ($emailTemplates as $template)
                    <tr>
                        <x-table.td>{{ $template->id }}</x-td>
                        <x-table.td>{{ $template->name }}</x-td>
                        <x-table.td>
                            <x-form
                                :action="route('email-template.destroy')"
                                delete
                                flat
                            >
                            @unless ($template->trashed())
                                <x-button.secondary
                                    type="submit"
                                    onclick="return confirm('{{ __('Are you sure you want to delete?') }}');"
                                >
                                    {{ __('Delete') }}
                                </x-button.secondary>
                            @else
                                <x-badge danger>
                                    {{  __('Deleted') }}
                                </x-badge>
                            @endunless
                            </x-form>
                        </x-table.td>
                    </tr>
                @endforeach
            </x-slot>
        </x-table>
        {{ $emailTemplates->links() }}
    </x-card>
</x-layouts.app>
