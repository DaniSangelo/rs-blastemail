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
            'send_at' => null,
            'send_when' => null,
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
            if ($map['send_when'] == 'now') {
                $map['send_at'] = now()->format('Y-m-d');
            } elseif ($map['send_when'] == 'later') {
                $rules = [
                    'send_at' => ['required', 'date', 'after:today']
                ];
            } else {
                $rules = ['send_when' => ['required']];
            }
        }

        $session = session('campaigns::create', $map);
        foreach ($session as $key => $value) {
            $newValue = data_get($session, $key);

            if (filled($newValue)) {
                $session[$key] = $newValue;
            }
        }

        if (($templateId = $session['email_template_id']) && blank($session['body'])) {
            $template = EmailTemplate::query()->select('body')->find($templateId);
            $session['body'] = $template?->body;
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
        unset($session['_token'], $session['send_when']);
        $session['track_click'] = $session['track_click'] ?: false;
        $session['track_open'] = $session['track_open'] ?: false;

        return $session;
    }
}