<div class="space-y-4">
    <x-form
        get
        action="{{ route('campaign.show', ['campaign' => $campaign->id, 'what' => $what]) }}"
    >
        <x-input.text name="search" placeholder="{{ __('Search') }}" value="{{ $search }}" />
    </x-form>
    <x-table :headers="[__('Name'), __('# Clicks'), __('Email')]">
        <x-slot name="body">
            <tr>
                <x-table.td>Daniel</x-table.td>
                <x-table.td>2</x-table.td>
                <x-table.td>daniel@mail.com</x-table.td>
            </tr>
        </x-slot>
    </x-table>
    {{-- {{ $campaigns->links() }} --}}
</div>
