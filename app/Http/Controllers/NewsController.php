<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\NewsReaction;
use Illuminate\Support\Facades\Cache;
use App\Events\UserReacted;
use App\Services\NewsServiceInterface;

class NewsController extends Controller
{
    protected $newsService;

    public function __construct(NewsServiceInterface $newsService)
    {
        $this->newsService = $newsService;
    }

    public function index()
    {
        return response()->json(
            $this->newsService->getTopHeadline()
        );
    }

    public function everything(Request $request)
    {
        $query = $request->get('q') ?: 'latest';

        $cacheKey = "news_" . md5($query);

        $news = Cache::remember($cacheKey, 300, function () use ($query) { //Stores data for 300 seconds
            return $this->newsService->getEverything($query);
        });
        
        return response()->json($news);

    }

    public function react(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'article_id' => 'required',
            'reaction' => 'required|in:like,dislike'
        ]);
        NewsReaction::updateOrCreate(
        [
            'user_id' => Auth::id(),
            'article_id' => $request->article_id
        ],
        [
            'reaction' => $request->reaction
        ]);
        event(new UserReacted($user, $request->article_id, $request->reaction));
        return response()->json(['message' => 'Saved']);
    }

    public function getCounts(Request $request)
    {
        $articleId = $request->query('articleId');
        $articleId = urldecode($articleId);
        $likes = NewsReaction::where('article_id', $articleId)
            ->where('reaction', 'like')
            ->count();

        $dislikes = NewsReaction::where('article_id', $articleId)
            ->where('reaction', 'dislike')
            ->count();

        return response()->json([
            'likes' => $likes,
            'dislikes' => $dislikes
        ]);
    }
}
