<?php

namespace App\Http\Controllers;

use App\Models\EmailList;

class SubscribersController extends Controller
{
    public function index(EmailList $emailList)
    {
        $search = request()->search;

        return view('subscriber.index', [
            'subscribers' => $emailList->subscribers()
                ->when($search, fn($query) => 
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%"))
                ->paginate(10),
            'emailList' => $emailList,
            'search' => $search,
        ]);
    }
}
