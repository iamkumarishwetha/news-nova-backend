<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use App\Events\UserReacted;

class HandleUserReaction
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserReacted $event)
    {
        Log::info('User reacted', [
            'user_id' => $event->user->id,
            'article_id' => $event->articleId,
            'reaction' => $event->reaction,
        ]);
    }
}
