<?php

namespace Mnv\Blog\Models;

use October\Rain\Database\Traits\Sluggable;
use RainLab\User\Models\User;

class Post extends \RainLab\Blog\Models\Post
{
    use Sluggable;

    protected $slugs = ['slug' => 'title'];

    /**
     * @inheritdoc
     */
    public function beforeSave()
    {
        parent::beforeSave();

        if (!$this->user_id) {
            $this->user_id = \Auth::getUser()->id;
        }

        $excerpt = explode("\n", \Html::strip($this->content_html));
        $length = 0;
        foreach ($excerpt as $index => $item) {
            $length += strlen($item);
            if ($length > 200) {
                $excerpt = array_slice($excerpt, 0, $index + 1);
                $excerpt[] = '&hellip;';
                break;
            }
        }
        $this->excerpt = implode('<br>', $excerpt);
    }

    public function getAvatarAttribute()
    {
        $user = User::find($this->user_id);
        $imageUrl = $user && $user->avatar
            ? $user->avatar->path
            : url('/plugins/clake/userextended/assets/img/default_user.png');
        return '<img src="' . $imageUrl . '">';
    }
}
