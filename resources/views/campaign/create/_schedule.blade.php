<div class="grid grid-cols-2 gap-4">
    <div>
        <x-input-label for="send_at" :value="__('Send_at')"/>
        <x-input.text
            id="send_at"
            name="send_at"
            type="date"
            :value="old('send_at', $data['send_at'])"
            autofocus
            class="block mt-1 w-full"
        />
        <x-input-error
            :messages="$errors->get('send_at')"
            class="mt-2"
        />
    </div>
</div>