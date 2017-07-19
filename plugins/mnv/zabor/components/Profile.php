<?php

namespace Mnv\Zabor\Components;

use Cms\Classes\ComponentBase;
use RainLab\User\Models\User;

class Profile extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'User Profile',
            'description' => 'Display profile of user.'
        ];
    }

    public function getUserInfo($userId)
    {
        $user = User::find((int)$userId);

        return $user;
    }

}