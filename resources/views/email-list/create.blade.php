<x-layouts.app>
    <x-slot name="header">
        <x-h2>
            {{ __('Email List') }} > {{ __('Create new list') }}
        </x-h2>
    </x-slot>

    <x-card>
        <x-form :action="route('email-list.store')" post enctype="multipart/form-data">
            <div>
                <x-input-label for="title" :value="__('Title')"/>
                <x-input.text
                    id="title"
                    name="title"
                    type="text"
                    :value="old('title')"
                    autofocus
                    class="block mt-1 w-full"
                />
                <x-input-error
                    :messages="$errors->get('title')"
                    class="mt-2"
                />
            </div>

            <div>
                <x-input-label for="listFile" :value="__('List File')"/>
                <x-input.text
                    id="listFile"
                    name="listFile"
                    type="file"
                    autofocus
                    accept=".csv"
                    class="block mt-1 w-full"
                />
                <x-input-error
                    :messages="$errors->get('listFile')"
                    class="mt-2"
                />
            </div>
            
            <div class="flex items-center space-x-4">
                <x-secondary-button type="reset">
                    {{ __('Cancel') }}
                </x-secondary-button>
                <x-primary-button type="submit">
                    {{ __('Save') }}
                </x-primary-button>
            </div>
        </x-form>
    </x-card>
</x-layouts.app>
