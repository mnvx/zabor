<?php

namespace Mnv\Reminder\Components;

use Cms\Classes\ComponentBase;

class Reminder extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Reminder',
            'description' => 'Remind for new posts and comments.'
        ];
    }

    public function testInfo()
    {
        return 'Test Info';
    }

    public function getSmartNews()
    {
        return [
            [
                'title' => 'Важная новость',
                'url' => 'link-to-smart-news',
                'excerpt' => 'Несколько слов из текста новости',
                'comments' => 43,
            ],
        ];
    }

    public function getUnreadNews()
    {
        return [
            [
                'title' => 'Первая непрочитанная новость',
                'url' => 'link-to-first-news',
                'comments' => 1,
            ],
            [
                'title' => 'Вторая непрочитанная новость',
                'url' => 'link-to-second-news',
                'comments' => 4,
            ],
        ];
    }

    public function getUnreadComments()
    {
        return [
            [
                'title' => 'Первая носость',
                'url' => 'link-to-first-news#comment1',
                'comments' => 15,
            ],
            [
                'title' => 'Вторая носость',
                'url' => 'link-to-third-news#comment2',
                'comments' => 1,
            ],
        ];
    }

}