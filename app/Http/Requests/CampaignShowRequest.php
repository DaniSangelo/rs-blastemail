<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class CampaignShowRequest extends FormRequest
{
    public function authorize()
    {
        $what = $this->route('what') ?: 'statistics';
        abort_unless(in_array($what, ['statistics', 'open', 'clicked']), Response::HTTP_NOT_FOUND);
        return true;
    }

    public function rules(): array
    {
        return [

        ];
    }

    public function checkWhat()
    {
        if(is_null($this->route('what'))) {
            return to_route('campaign.show', ['campaign' => $this->route('campaign'), 'what' => 'statistics']);
        }
    }
}