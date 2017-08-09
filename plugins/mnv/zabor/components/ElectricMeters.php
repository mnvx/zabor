<?php

namespace Mnv\Zabor\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use Mnv\Zabor\Models\ElectricMeter;
use RainLab\User\Facades\Auth;

class ElectricMeters extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Electric counters',
            'description' => 'List of electric counters.'
        ];
    }

    public function defineProperties()
    {
        return [
            'servicePage' => [
                'title'       => 'Url',
                'description' => 'Страница сервиса',
                'type'        => 'string',
                'default'     => 'http://askue-mo.ru/',
                'group'       => 'Links',
            ],
        ];
    }

    public function getServicePageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function getCounters()
    {
        $user = Auth::getUser();
        return ElectricMeter::where('user_id', '=' , $user->id)->get();
    }

    public function getStartDate()
    {
        $date = new \DateTime();
        $date->setDate($date->format('Y'), $date->format('m'), 1);
        $date->sub(new \DateInterval('P1M'));
        return $date->format('Y-m-d');
    }
}