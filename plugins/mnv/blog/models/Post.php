<?php

namespace Mnv\Blog\Models;

use October\Rain\Database\Traits\Sluggable;

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
}
