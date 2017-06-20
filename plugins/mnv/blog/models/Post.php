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
        if (!$this->excerpt) {
            $this->excerpt = \Html::strip($this->content_html);
        }
    }
}
