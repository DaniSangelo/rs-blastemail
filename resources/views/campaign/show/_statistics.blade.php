<div class="flex flex-col gap-4">
    <x-alert success noIcon :title="__('Your campaign has been sent to 10 subscribers')" />
    <div class="grid grid-cols-3 gap-5">
        <x-dashboard.card heading="01" subheading="{{ __('Opens') }}" />
        <x-dashboard.card heading="02" subheading="{{ __('Unique Opens') }}" />
        <x-dashboard.card heading="30%" subheading="{{ __('Open rate') }}" />
        <x-dashboard.card heading="0" subheading="{{ __('Clicks') }}" />
        <x-dashboard.card heading="0" subheading="{{ __('Unique Clicks') }}" />
        <x-dashboard.card heading="10%" subheading="{{ __('Clicks rate') }}" />
    </div>
</div>