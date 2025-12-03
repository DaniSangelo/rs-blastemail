<?php

namespace App\Http\Requests;

use App\Models\EmailTemplate;
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
                'email_list_id' => ['required', 'exists:email_lists,id'],
                'email_template_id' => ['required', 'exists:email_templates,id'],
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

        if ($templateId = $session['email_template_id'] && blank($session['body'])) {
            $template = EmailTemplate::find($templateId)->select('body')->first();
            $session['body'] = $template->body;
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