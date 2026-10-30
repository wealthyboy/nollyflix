<?php

namespace App\Http\Controllers\Admin\Videos;

use App\Activity;
use App\ContentAdvisory;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContentAdvisoriesController extends Controller
{
    public function index()
    {
        $advisories = ContentAdvisory::ordered()->withCount('videos')->get();

        return view('admin.content_advisories.index', compact('advisories'));
    }

    public function store(Request $request)
    {
        abort_unless(User::userHasPermission(2), 403);
        $data = $this->validated($request);
        $advisory = ContentAdvisory::create($data + ['slug' => str_slug($data['name'])]);
        (new Activity)->Log('Created content advisory '.$advisory->name);

        return redirect()->route('content-advisories.index')->with('success', 'Content advisory added.');
    }

    public function edit(ContentAdvisory $contentAdvisory)
    {
        abort_unless(User::userHasPermission(3), 403);

        return view('admin.content_advisories.edit', ['advisory' => $contentAdvisory]);
    }

    public function update(Request $request, ContentAdvisory $contentAdvisory)
    {
        abort_unless(User::userHasPermission(3), 403);
        // Keep the stable slug so the default seeder remains safe to re-run.
        $contentAdvisory->update($this->validated($request, $contentAdvisory));
        (new Activity)->Log('Updated content advisory '.$contentAdvisory->name);

        return redirect()->route('content-advisories.index')->with('success', 'Content advisory updated.');
    }

    private function validated(Request $request, ContentAdvisory $advisory = null)
    {
        if (is_string($request->input('name'))) {
            $request->merge(['name' => trim($request->input('name'))]);
        }
        $rules = [
            'name' => ['required', 'string', 'max:100', Rule::unique('content_advisories', 'name')->ignore(optional($advisory)->id)],
            'sort_order' => 'nullable|integer|min:0|max:100000',
        ];
        if (!$advisory) {
            $request->merge(['slug' => is_string($request->input('name')) ? str_slug($request->input('name')) : '']);
            $rules['slug'] = 'required|string|max:100|unique:content_advisories,slug';
        }
        $data = $request->validate($rules, ['slug.required' => 'Enter a label containing letters or numbers.']);

        return ['name' => $data['name'], 'sort_order' => (int) ($data['sort_order'] ?? 0)];
    }
}
