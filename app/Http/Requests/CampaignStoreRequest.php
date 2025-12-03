<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CampaignStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $tab = $this->route('tab');
        $rules = [];
        $map = array_merge([
            'name' =>  null,
            'subject' =>  null,
            'email_list_id' => null,
            'email_template_id' => null,
            'body' => null,
            'track_click' => null,
            'track_open' => null,
            'send_at' => null
        ], request()->all());

        if (blank($tab)) {
            $rules = [
                'name' => ['required', 'string', 'max:255'],
                'subject' => ['required', 'string', 'max:50'],
                'email_list_id' => ['required', 'exists:email_list,id'],
                'email_template_id' => ['required', 'exists:email_template,id'],
            ];
        } elseif ($tab == 'template') {
            $rules = [
                'body' => ['required']
            ];
        } elseif ($tab == 'schedule') {
            $rules = [
                'send_at' => ['required', 'date']
            ];
        }

        $session = session('campaigns::create', $map);
        foreach($session as $key => $value) {
            $newValue = data_get($map, $key);

            if (in_array($key, ['track_click', 'track_open'])) {
                $session[$key] = $newValue;
            }elseif (filled($newValue)) {
                $session[$key] = $newValue;
            }
        }

        session()->put('campaigns::create', $session);
        return $rules;
    }

    public function getToRoute()
    {
        $tab = $this->route('tab');
        if (blank($tab)) {
            return route('campaign.create', ['tab' => 'template']);
        } elseif ($tab == 'template') {
            return route('campaign.create', ['tab' => 'schedule']);
        } elseif ($tab == 'schedule') {
            return route('campaign.index');
        }
    }

    public function getData()
    {
        $session = session()->get('campaigns::create');
        unset($session['_token']);
        $session['track_click'] = $session['track_click'] ?: false;
        $session['track_open'] = $session['track_open'] ?: false;

        return $session;
    }
}
/*

        $toRoute = $tab;
        $map = array_merge([
            'name' => null,
            'subject' => null,
            'email_list_id' => null,
            'email_template_id' => null,
            'body' => null,
            'track_click' => null,
            'track_open' => null,
            'send_at' => null,
        ], request()->all());

        request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:50'],
            'email_list_id' => ['nullable'],
            'email_template_id' => ['nullable'],
            'body' => ['nullable'],
            'track_click' => ['nullable'],
            'track_open' => ['nullable'],
            'send_at' => ['nullable'],
        ]);

        if (blank($tab)) {
            request()->validate([
                'name' => ['required', 'string', 'max:255'],
                'subject' => ['required', 'string', 'max:50'],
                'email_list_id' => ['nullable'],
                'email_template_id' => ['nullable'],
            ]);
            $toRoute = route('campaign.create', ['tab' => 'template']);
        } elseif ($tab == 'template') {
            request()->validate([
                'body' => ['required']
            ]);
            $toRoute = route('campaign.create', ['tab' => 'schedule']);
        } elseif ($tab == 'schedule') {
            request()->validate([
                'send_at' => ['required', 'date']
            ]);
            $toRoute = route('campaign.index');
        }

        $session = session('campaigns::create');
        foreach($session as $key) {
            $session[$key] = filled(data_get($map, $key)) ? data_get($map, $key) : null;
        }

        session()->put('campaigns::create', $session);
*/
