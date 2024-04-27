<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $newskey = config('news.newsapi');
        $response = Http::get('https://newsapi.org/v2/top-headlines?country=us&category=business&apiKey='.$newskey);
        $news = $response->json();
        $data = $news['articles'];

        $newsdata = collect($data)->map(function ($item) {
            if ($item['author']) {
                $key = "api_response_{$item['url']}";
                $updatedInfo = Cache::remember($key, now()->addMinutes(1), function () use ($item) {
                    $response = Http::get('https://randomuser.me/api/');
                    return $response->successful() ? $response->json() : null;
                });

                $item['author'] = $updatedInfo['results'][0]['name']['first'] . ' ' . $updatedInfo['results'][0]['name']['last'];
            }
            return $item;
        });

        $newsdata = $this->paginate($newsdata, 10);

        $newsdata->setPath('');

        return view('home', compact('newsdata'));
    }

    public function paginate($items, $perPage, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function news(Request $request)
    {
        return $request;
    }
}
