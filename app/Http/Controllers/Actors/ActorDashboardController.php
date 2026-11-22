<?php

namespace App\Http\Controllers\Actors;

use App\FilmEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ActorDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        abort_unless($user->type === 'casts', 403);
        $data = $request->validate([
            'from' => 'nullable|date_format:Y-m-d',
            'to' => 'nullable|date_format:Y-m-d|after_or_equal:from',
        ]);
        $from = $data['from'] ?? null;
        $to = $data['to'] ?? null;
        $period = function ($query) use ($from, $to) {
            if ($from) $query->where('created_at', '>=', $from.' 00:00:00');
            if ($to) $query->where('created_at', '<', \Carbon\Carbon::parse($to)->addDay()->toDateString());
        };
        $films = $user->cast_videos()->withCount([
            'filmEvents as clicks' => function ($query) use ($period) { $query->where('kind', 'click'); $period($query); },
            'filmEvents as watches' => function ($query) use ($period) { $query->where('kind', 'watch'); $period($query); },
        ])->orderBy('title')->get();
        $trackingStarted = FilmEvent::whereIn('video_id', $films->pluck('id'))->min('created_at');
        $page_title = 'Actor Dashboard';

        return view('actors.dashboard', compact('user', 'films', 'from', 'to', 'trackingStarted', 'page_title'));
    }
}
