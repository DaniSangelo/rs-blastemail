<?php

namespace App\Http\Controllers;

use App\Models\CampaignEmail;

class TrackingController extends Controller
{
    public function openings(CampaignEmail $mail)
    {
        if (!$mail->campaign->track_open) return;
        $mail->openings++;
        $mail->save();
    }

    public function clicks(CampaignEmail $mail)
    {
        if ($mail->campaign->track_click) {
            $mail->clicks++;
            $mail->save();
        };

        return redirect()->away(
            request()->get('forward')
        );
    }
}
