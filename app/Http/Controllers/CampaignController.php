<?php

namespace App\Http\Controllers;

use App\Http\Requests\CampaignStoreRequest;
use App\Models\Campaign;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index()
    {
        $search = request()->get('search', null);
        $showTrash = request()->get('showTrash', false);

        return view('campaign.index', [
            'campaigns' => Campaign::query()
                ->when($search, fn($query) => $query->where('name', 'like', "%{$search}%"))
                ->when($showTrash, fn($query) => $query->withTrashed())
                ->paginate(5)
                ->appends(compact('search', 'showTrash')),
                'search' => $search,
                'showTrash' => $showTrash
        ]);
    }

    public function destroy(Campaign $campaign)
    {
        $campaign->delete();

        return back()->with('message', 'Campaign deleted successfully');
    }

    public function restore(Campaign $campaign)
    {
        $campaign->restore();
        return back()->with('message', 'Campaign restored successfully');
    }

    public function create(?string $tab = null)
    {
        return view('campaign.create', [
            'tab' => $tab,
            'form' => match($tab) {
                'template' => '_template',
                'schedule' => '_schedule',
                default => '_config'
            },
            'data' => session()->get('campaigns::create', [
                'name' => null,
                'subject' => null,
                'email_list_id' => null,
                'email_template_id' => null,
                'body' => null,
                'track_click' => null,
                'track_open' => null,
                'send_at' => null,
                ])
            ]);
    }

    public function store(CampaignStoreRequest $request, ?string $tab = null)
    {
        $data = $request->getData();
        $toRoute = $request->getToRoute();
        if ($tab == 'schedule') {
            session()->forget('campaigns::create');
            Campaign::create($data);
        }

        return response()->redirectTo($toRoute);
    }
}
