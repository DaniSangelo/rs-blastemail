<x-layouts.app>
    <x-slot name="header">
        <x-h2>
            {{ __('Templates') }} > {{ $emailTemplate->name }} > {{ __('Edit') }}
        </x-h2>
    </x-slot>

    <x-card>
        <x-form :action="route('email-template.update', $emailTemplate)" put>
            <div>
                <x-input-label for="name" :value="__('Name')"/>
                <x-input.text
                    id="name"
                    name="name"
                    type="text"
                    :value="old('name', $emailTemplate->name)"
                    autofocus
                    class="block mt-1 w-full"
                />
                <x-input-error
                    :messages="$errors->get('name')"
                    class="mt-2"
                />
            </div>

            <div>
                <x-input-label for="body" :value="__('Body')"/>
                <x-input.text
                    id="body"
                    name="body"
                    type="text"
                    :value="old('body', $emailTemplate->body)"
                    autofocus
                    class="block mt-1 w-full"
                />
                <x-input-error
                    :messages="$errors->get('name')"
                    class="mt-2"
                />
            </div>
            
            <div class="flex items-center space-x-4">
                <x-button.link :href="route('email-template.index')" secondary>
                    {{ __('Cancel') }}
                </x-button.link>
                <x-button type="submit">
                    {{ __('Save') }}
                </x-button>
            </div>
        </x-form>
    </x-card>
</x-layouts.app>
