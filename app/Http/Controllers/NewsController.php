<?php
namespace App\Http\Controllers;

use App\Events\UserReacted;
use App\Models\NewsReaction;
use App\Services\NewsServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

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
        $query    = trim($request->get('q')) ?: 'latest';
        $page     = $request->get('page', 1);
        $pageSize = $request->get('pageSize', 5);

        $cacheKey = "news_" . md5($query . "_{$page}_{$pageSize}");

        $news = Cache::remember($cacheKey, 300, function () use ($query, $page, $pageSize) {
            return $this->newsService->getEverything($query, $page, $pageSize);
        });

        return response()->json($news);
    }

    public function react(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'article_id' => 'required',
            'reaction'   => 'required|in:like,dislike',
        ]);
        NewsReaction::updateOrCreate(
            [
                'user_id'    => Auth::id(),
                'article_id' => $request->article_id,
            ],
            [
                'reaction' => $request->reaction,
            ]);
        event(new UserReacted($user, $request->article_id, $request->reaction));
        return response()->json(['message' => 'Saved']);
    }

    public function getCounts(Request $request)
    {
        $articleId = $request->query('articleId');
        $articleId = urldecode($articleId);
        $likes     = NewsReaction::where('article_id', $articleId)
            ->where('reaction', 'like')
            ->count();

        $dislikes = NewsReaction::where('article_id', $articleId)
            ->where('reaction', 'dislike')
            ->count();

        return response()->json([
            'likes'    => $likes,
            'dislikes' => $dislikes,
        ]);
    }
}
