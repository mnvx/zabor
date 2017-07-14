<?php

namespace Mnv\Blog\Models;

use Mnv\Blog\Models\Catalog\SurveyQuestionType;
use October\Rain\Database\Traits\Sluggable;
use October\Rain\Exception\ValidationException;

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

    public function __construct(array $attributes = [])
    {
        $this->rules['title'] = 'question_required|question_type_required|question_type_correct';
        parent::__construct($attributes);
    }

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

    /**
     * @inheritdoc
     */
    public function afterSave()
    {
        // Store survey questions
        $questions = request()->get('survey', []);
        foreach ($questions as $key => $question) {
            SurveyQuestion::insert([
                'post_id' => $this->id,
                'question' => isset($question['question']) ? $question['question'] : null,
                'type' => SurveyQuestionType::getIdByCode(isset($question['type']) ? $question['type'] : null),
                'item_order' => $key,
            ]);
        }
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
