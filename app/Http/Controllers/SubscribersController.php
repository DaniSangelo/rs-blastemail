<?php

namespace App\Http\Controllers;

use App\Models\EmailList;
use App\Models\Subscriber;

class SubscribersController extends Controller
{
    public function index(EmailList $emailList)
    {
        $search = request()->search;
        $showTrash = request()->get('showTrash', false);
        return view('subscriber.index', [
            'subscribers' => $emailList->subscribers()
                ->when($search, fn($query) => 
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%"))
                ->when($showTrash, fn($query) => $query->withTrashed())
                ->paginate(10)
                ->appends(compact('search', 'showTrash')),
            'emailList' => $emailList,
            'search' => $search,
            'showTrash' => $showTrash,
        ]);
    }

    public function destroy(mixed $_, Subscriber $subscriber)
    {
        $subscriber->delete();
        return back()->with('message', __('Subscriber removed from the list'));
    }
}
