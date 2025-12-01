<?php

namespace App\Http\Controllers;

use App\Models\EmailList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmailListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->search;

        $emailList = EmailList::query()
            ->when($search, function ($query, $search) {
                $query->where("title","like","%$search%");
            })
            ->paginate(5)
            ->appends(compact("search"));

        return view('email-list.index',[
            'emailLists' => $emailList,
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('email-list.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required','string','max:255'],
            'listFile' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        $items = $this->getEmailsFromCsvFile($request->file('listFile'));

        DB::transaction(function() use ($request, $items) {
            $emailList = EmailList::query()->create([
                'title' => $request->title,
            ]);
    
            $emailList->subscribers()->createMany($items);
        });
        
        return to_route('email-list.index');
    }

    private function getEmailsFromCsvFile($file)
    {
        $fileHandle = fopen($file->getRealPath(),'r');
        while (($row = fgetcsv($fileHandle, null, ';')) !== false) {
            if($row[0] == 'Name' || $row[1] == 'Email') continue;
            $items[] = [
                'name' => $row[0],
                'email'=> $row[1],
            ];
        }
        fclose($fileHandle);

        return $items;
    }

    /**
     * Display the specified resource.
     */
    public function show(EmailList $emailList)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmailList $emailList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmailList $emailList)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmailList $emailList)
    {
        //
    }
}
