<?php

namespace App\Http\Controllers\Admin\Videos;

use App\Activity;
use App\Genre;
use App\Http\Controllers\Controller;
use App\Section;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class VideoMetadataController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => ['required', Rule::in(['genre', 'section', 'cast', 'filmer'])],
            'name' => 'required|string|max:100',
            'last_name' => 'nullable|required_if:type,cast|required_if:type,filmer|string|max:100',
        ]);

        if ($request->type === 'genre' || $request->type === 'section') {
            $modelClass = $request->type === 'genre' ? Genre::class : Section::class;
            $table = $request->type === 'genre' ? 'genres' : 'sections';
            $request->validate(['name' => 'unique:'.$table.',name']);

            $item = new $modelClass;
            $item->name = $request->name;
            $item->slug = str_slug($request->name);
            $item->sort_order = 0;
            $item->save();
            $label = $item->name;
        } else {
            $baseUsername = str_slug($request->name.' '.$request->last_name);
            $username = $baseUsername;
            while (User::where('username', $username)->exists()) {
                $username = $baseUsername.'-'.Str::lower(Str::random(4));
            }

            $item = new User;
            $item->name = $request->name;
            $item->last_name = $request->last_name;
            $item->slug = $username;
            $item->username = $username;
            $item->email = $username.'-'.Str::lower(Str::random(8)).'@nollyflix.local';
            $item->description = 'Created from the video upload form.';
            $item->type = $request->type === 'cast' ? 'casts' : 'filmakers';
            $item->password = bcrypt(Str::random(32));
            $item->save();
            $label = trim($item->name.' '.$item->last_name);
        }

        (new Activity)->Log('Created '.$request->type.' '.$label.' from the video upload form');

        return response()->json([
            'message' => ucfirst($request->type).' created successfully.',
            'item' => ['id' => $item->id, 'label' => $label, 'type' => $request->type],
        ], 201);
    }
}
