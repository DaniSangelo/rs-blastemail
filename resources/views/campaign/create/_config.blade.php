<div class="grid grid-cols-2 gap-4">
    <div>
        <x-input-label for="name" :value="__('Name')"/>
        <x-input.text
            id="name"
            name="name"
            type="text"
            :value="old('name', $data['name'])"
            autofocus
            class="block mt-1 w-full"
        />
        <x-input-error
            :messages="$errors->get('name')"
            class="mt-2"
        />
    </div>
    <div>
        <x-input-label for="subject" :value="__('Subject')"/>
        <x-input.text
            id="subject"
            name="subject"
            type="text"
            :value="old('subject', $data['subject'])"
            autofocus
            class="block mt-1 w-full"
        />
        <x-input-error
            :messages="$errors->get('subject')"
            class="mt-2"
        />
    </div>
    <div>
        <x-input-label for="email_list_id" :value="__('Email List')"/>

        <x-select name="email_list_id" id="email_list_id">
            <option
                value=""
                @if (blank(old('email_list_id', $data['email_list_id']))) selected @endif
            >
                {{__('Select an email list')}}
            </option>
            @foreach ($emailLists as $emailList)
                <option
                    value="{{ $emailList->id }}"
                    @if (old('email_list_id', $data['email_list_id']) == $emailList->id) selected @endif
                >{{ $emailList->title }}</option>
            @endforeach
        </x-select>
        <x-input-error
            :messages="$errors->get("email_list_id")"
            class="mt-2"
        />
    </div>
    <div>
        <x-input-label for="email_template_id" :value="__('Template')"/>
        <x-select name="email_template_id" id="email_template_id">
            <option
                value=""
                @if (blank(old('email_template_id', $data['email_template_id']))) selected @endif
            >
                {{__('Select an email template')}}
            </option>
            @foreach ($emailTemplates as $template)
                <option
                    value="{{ $template->id }}"
                    @if (old('email_template_id', $data['email_template_id']) == $template->id) selected @endif
                >{{ $template->name }}</option>
            @endforeach
        </x-select>
        <x-input-error
            :messages="$errors->get('email_template_id')"
            class="mt-2"
        />
    </div>
    <div>
        <x-input-label for="track_click" :value="__('Track Click')"/>
        <x-input.text
            id="track_click"
            name="track_click"
            type="text"
            :value="old('track_click', $data['track_click'])"
            autofocus
            class="block mt-1 w-full"
        />
        <x-input-error
            :messages="$errors->get('track_click')"
            class="mt-2"
        />
    </div>
    <div>
        <x-input-label for="track_open" :value="__('Track Open')"/>
        <x-input.text
            id="track_open"
            name="track_open"
            type="text"
            :value="old('track_open', $data['track_open'])"
            autofocus
            class="block mt-1 w-full"
        />
        <x-input-error
            :messages="$errors->get('track_open')"
            class="mt-2"
        />
    </div>
</div>