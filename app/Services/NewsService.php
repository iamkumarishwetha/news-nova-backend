<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class NewsService implements NewsServiceInterface
{
    public function getTopHeadline()
    {
        return Http::get('https://newsapi.org/v2/top-headlines', [
            'country' => 'us',
            'apiKey' => env('NEWS_API_KEY')
        ])->json();
    }

    public function getEverything($query = 'latest', $page = 1, $pageSize = 5)
    {
        return Http::get('https://newsapi.org/v2/everything', [
            'q' => $query,
            'page' => $page,
            'pageSize' => $pageSize,
            'sortBy' => 'publishedAt',
            'apiKey' => env('NEWS_API_KEY')
        ])->json();
    }
}