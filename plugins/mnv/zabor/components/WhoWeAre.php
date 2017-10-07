<?php

namespace Mnv\Zabor\Components;

use Cms\Classes\ComponentBase;
use RainLab\User\Models\User;

class WhoWeAre extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Who We Are',
            'description' => 'List of users.'
        ];
    }

    public function getUserList()
    {
        return User::orderByRaw("(case when regexp_replace(zabor_stead, '[^\\d]+', '', 'g') ~ '[\\d]+' then regexp_replace(zabor_stead, '[^\\d]+', '', 'g') else '0' end) ::int")->get();
    }

    public function getUserProfileUrl($userId)
    {
        return 'user/profile/' . $userId;
    }

}