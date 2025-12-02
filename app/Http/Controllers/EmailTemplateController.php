<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->get('search', null);
        $showTrash = request()->get('showTrash', false);

        return view('email-template.index', [
            'emailTemplates' => EmailTemplate::query()
                ->when($search, fn($query) => $query->where('name', 'like', "%{$search}%"))
                ->when($showTrash, fn($query) => $query->withTrashed())
                ->paginate(5)
                ->appends(compact('search')),
                'search' => $search,
                'showTrash' => $showTrash
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('email-template.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'body' => 'required',
        ]);

        EmailTemplate::create($data);

        return to_route('email-template.index')
            ->with('message', __('Template created successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(EmailTemplate $emailTemplate)
    {
        return view('email-template.show', compact('emailTemplate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmailTemplate $emailTemplate)
    {
        return view('email-template.edit', compact('emailTemplate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'body' => 'required',
        ]);

        $emailTemplate->fill($data);
        $emailTemplate->save();

        return to_route('email-template.index')
            ->with('message', __('Template updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmailTemplate $emailTemplate)
    {
        $emailTemplate->delete();

        return to_route('email-template.index')
            ->with('message', __('Template deleted successfully'));
    }
}
