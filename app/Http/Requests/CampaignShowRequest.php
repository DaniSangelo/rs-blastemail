<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class CampaignShowRequest extends FormRequest
{
    public function authorize()
    {
        $campaign = $this->route('campaign');
        $what = $this->route('what');

        if(is_null($what)) {
            return to_route('campaign.show', ['campaign' => $campaign->id, 'what' => 'statistics']);
        }

        abort_unless(in_array($what, ['statistics', 'open', 'clicked']), Response::HTTP_NOT_FOUND);
        return true;
    }

    public function rules(): array
    {
        return [

        ];
    }
}