<?php

namespace App\Http\Controllers;

use App\Http\Requests\CampaignShowRequest;
use App\Http\Requests\CampaignStoreRequest;
use App\Jobs\SendEmailsCampaignJob;
use App\Models\Campaign;
use App\Models\CampaignEmail;
use App\Models\EmailList;
use App\Models\EmailTemplate;
use Illuminate\Support\Traits\Conditionable;

class CampaignController extends Controller
{
    use Conditionable;

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
        $data = session()->get('campaigns::create', [
            'name' => null,
            'subject' => null,
            'email_list_id' => null,
            'email_template_id' => null,
            'body' => null,
            'track_click' => null,
            'track_open' => null,
            'send_at' => null,
            'send_when' => 'now',
        ]);
        return view('campaign.create', array_merge(
            $this->when(blank($tab), fn () => [
                'emailLists' => EmailList::query()->select(['id', 'title'])->orderBy('title')->get(),
                'emailTemplates' => EmailTemplate::query()->select(['id','name'])->orderBy('name')->get(),
            ], fn () => []),
            $this->when($tab == 'schedule', fn () =>[
                'countEmails' => EmailList::find($data['email_list_id'])->subscribers->count(),
                'templateName' => EmailTemplate::find($data['email_template_id'])->name,
            ], fn () => []),
            [
                'tab' => $tab,
                'form' => match($tab) {
                    'template' => '_template',
                    'schedule' => '_schedule',
                    default => '_config'
                },
                'data' => $data,
            ]
        ));
    }

    public function store(CampaignStoreRequest $request, ?string $tab = null)
    {
        $data = $request->getData();
        $toRoute = $request->getToRoute();
        if ($tab == 'schedule') {
            session()->forget('campaigns::create');
            $campaign = Campaign::create($data);
            SendEmailsCampaignJob::dispatchAfterResponse($campaign);
        }

        return response()->redirectTo($toRoute);
    }

    public function show(CampaignShowRequest $request, Campaign $campaign, ?string $what = null)
    {
        if($redirect = $request->checkWhat()) {
            return $redirect;
        }

        $search = request()->get('search', null);
        $query = $campaign
            ->mails()
            ->selectRaw("
                count(subscriber_id) as total_subscribers,
                sum(openings) as total_openings,
                count(case when openings > 0 then subscriber_id end) as unique_openings,
                sum(clicks) as total_clicks,
                count(case when clicks > 0 then subscriber_id end) as unique_clicks            
            ")
            ->first();

        return view('campaign.show', compact('campaign', 'what', 'search', 'query'));
    }
}
