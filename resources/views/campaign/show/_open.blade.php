<div class="space-y-4">
    <x-form
        get
        action="{{ route('campaign.show', ['campaign' => $campaign->id, 'what' => $what]) }}"
    >
        <x-input.text name="search" placeholder="{{ __('Search') }}" value="{{ $search }}" />
    </x-form>
    <x-table :headers="[__('Name'), __('Email'), __('# Openings')]">
        <x-slot name="body">
            @foreach ($query as $item)
                <tr>
                    <x-table.td>{{ $item->subscriber->name }}</x-table.td>
                    <x-table.td>{{ $item->subscriber->email }}</x-table.td>
                    <x-table.td>{{ $item->openings }}</x-table.td>
                </tr>
            @endforeach
        </x-slot>
    </x-table>
    {{ $query->links() }}
</div>
