<?php

namespace Mnv\Blog\Components;

use Cms\Classes\ComponentBase;

class Survey extends ComponentBase
{
    protected $blogPostPrefixUrl = 'blog/post/';

    public function componentDetails()
    {
        return [
            'name' => 'mnv.blog::lang.survey.name',
            'description' => 'mnv.blog::lang.survey.description'
        ];
    }

    public function onRun()
    {
        parent::onRun();

        $this->addCss('/plugins/mnv/blog/assets/css/survey.css');
        $this->addJs('/plugins/mnv/blog/assets/js/survey.js');
        $this->addJs('/modules/system/assets/ui/vendor/mustache/mustache.js');
    }

}