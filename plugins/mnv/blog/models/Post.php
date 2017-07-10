<?php

namespace Mnv\Blog\Models;

use October\Rain\Database\Traits\Sluggable;

class Post extends \RainLab\Blog\Models\Post
{
    use Sluggable;

    protected $slugs = ['slug' => 'title'];

    public function featured_images()
    {
        return $this->hasMany('System\Models\File', 'attachment_id')->where('field', '=', 'featured_images');
    }

    public function files()
    {
        return $this->hasMany('System\Models\File', 'attachment_id')->where('field', '=', 'files');
    }

    public $belongsTo = [
        'front_user' => ['RainLab\User\Models\User']
    ];

    public $attachMany = [
        // parent
        'featured_images' => [
            'System\Models\File',
            'order' => 'sort_order',
            'public' => false,
        ],
        'content_images' => ['System\Models\File'],
        'files' => [
            'System\Models\File',
            'order' => 'sort_order',
            'public' => false,
        ],
    ];

    /**
     * @inheritdoc
     */
    public function beforeSave()
    {
        foreach ($this->featured_images as $img)
        {
            $img->is_public = false;
        }
        parent::beforeSave();

        if (!$this->front_user_id) {
            $this->front_user_id = \Auth::getUser()->id;
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
        $user = $this->front_user;
        $imageUrl = $user && $user->avatar
            ? $user->avatar->path
            : url('/plugins/clake/userextended/assets/img/default_user.png');
        return '<img src="' . $imageUrl . '">';
    }

}
