<div class="flex flex-col gap-4">
    <x-alert success noIcon :title="__('Your campaign has been sent to ' . $query['total_subscribers'] . ' subscribers')" />
    <div class="grid grid-cols-3 gap-5">
        <x-dashboard.card heading="{{ $query['total_openings'] }}" subheading="{{ __('Opens') }}" />
        <x-dashboard.card heading="{{ $query['unique_openings'] }}" subheading="{{ __('Unique Opens') }}" />
        <x-dashboard.card heading="{{ round($query['unique_openings'] / $query['total_subscribers'] * 100, 2) }}%" subheading="{{ __('Open rate') }}" />
        <x-dashboard.card heading="{{ $query['total_clicks'] }}" subheading="{{ __('Clicks') }}" />
        <x-dashboard.card heading="{{ $query['unique_clicks'] }}" subheading="{{ __('Unique Clicks') }}" />
        <x-dashboard.card heading="{{ round($query['unique_clicks'] / $query['total_subscribers'] * 100, 2) }}%" subheading="{{ __('Clicks rate') }}" />
    </div>
</div>