<?php

namespace App\Http\Controllers\Api\WatchList;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\WatchList;



class WatchListController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = auth('api')->user();

        $perPage = (int) $request->query('per_page', 10);

        $query = $user->movies()
            ->with(['video', 'cart.video']);

        $videos = $query->paginate($perPage);

        return WatchList::collection($videos);
    }
}
