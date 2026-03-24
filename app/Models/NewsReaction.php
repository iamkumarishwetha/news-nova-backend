<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsReaction extends Model
{
    protected $fillable = [
        'user_id',
        'article_id',
        'reaction'
    ];
}
