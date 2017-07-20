<?php

namespace Mnv\Reminder\Models;

use Illuminate\Support\Facades\DB;
use Model;
use RainLab\User\Facades\Auth;

/**
 * Model
 */
class NewsRead extends Model
{
    protected $primaryKey = 'post_id'; // fake

    /**
     * @var string The database table used by the model.
     */
    public $table = 'mnv_news_read';

    protected $fillable = [
        'user_id',
        'post_id',
        'read_at',
    ];

    public $timestamps = false;

    /**
     * @param int $postId
     */
    public static function markPostAsRead($postId)
    {
        $user = Auth::getUser();
        NewsRead::updateOrCreate(
            [ 'user_id' => $user->id, 'post_id' => $postId ],
            [ 'read_at' => DB::raw("NOW() at time zone 'utc'") ]
        );
    }
}