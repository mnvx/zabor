<?php

namespace Mnv\Reminder\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\DB;
use Mnv\Blog\Models\Post;
use Mnv\Reminder\Models\NewsRead;
use RainLab\User\Facades\Auth;
use Taras\Comments\Models\Comments;

class Reminder extends ComponentBase
{
    protected $blogPostPrefixUrl = 'blog/post/';

    public function componentDetails()
    {
        return [
            'name' => 'Reminder',
            'description' => 'Remind for new posts and comments.'
        ];
    }

    public function getSmartNews()
    {
        return [
            [
                'title' => 'Важная новость',
                'url' => 'link-to-smart-news',
                'excerpt' => 'Несколько слов из текста новости',
                'comment_count' => 43,
            ],
        ];
    }

    public function getUnreadNews()
    {
        $user = Auth::getUser();
        $postTable = (new Post())->getTable();
        $newsReadTable = (new NewsRead())->getTable();
        $commentsTable = (new Comments())->getTable();

        $query = Post::select(
                $postTable  . '.title',
                DB::raw("'" . $this->blogPostPrefixUrl . "' || " . $postTable . '.slug as url'),
                DB::raw('count(c.id) as comment_count')
            )
            ->leftJoin($newsReadTable . ' as nr', function($join) use ($postTable, $user) {
                $join->on('nr.post_id', '=', $postTable . '.id')
                    ->where('nr.user_id', '=', $user->id);
            })
            ->leftJoin($commentsTable . ' as c', 'c.url', '=', DB::raw("'" . $this->blogPostPrefixUrl . "' || " . $postTable . '.slug'))
            ->where($postTable . '.created_at', '>', DB::raw("coalesce(nr.read_at, '" . $user->created_at . "'::date)"))
            ->where($postTable . '.published', '=', true)
            ->groupBy($postTable . '.id')
            ->orderBy($postTable . '.published_at', 'desc');

        return $query->get();
    }

    public function getUnreadComments()
    {
        $user = Auth::getUser();
        $postTable = (new Post())->getTable();
        $newsReadTable = (new NewsRead())->getTable();
        $commentsTable = (new Comments())->getTable();

        $query = Post::select(
                $postTable  . '.title',
                DB::raw("'" . $this->blogPostPrefixUrl . "' || " . $postTable . '.slug as url'),
                DB::raw('count(c.id) as comment_count')
            )
            ->leftJoin($newsReadTable . ' as nr', function($join) use ($postTable, $user) {
                $join->on('nr.post_id', '=', $postTable . '.id')
                    ->where('nr.user_id', '=', $user->id);
            })
            ->join($commentsTable . ' as c', 'c.url', '=', DB::raw("'" . $this->blogPostPrefixUrl . "' || " . $postTable . '.slug'))
            ->where('c.created_at', '>', DB::raw("coalesce(nr.read_at, '" . $user->created_at . "'::date)"))
            ->where($postTable . '.published', '=', true)
            ->groupBy($postTable . '.id')
            ->orderBy($postTable . '.published_at', 'desc');

        return $query->get();
    }

}