<?php

namespace Mnv\SecureResources\Models;

use RainLab\User\Facades\Auth;

class Permissions
{
    const WHITE_GROUP = 'member';

    public static function isUserInWhiteGroup($user = null)
    {
        if (!$user) {
            $user = Auth::getUser();
        }
        if ($user) {
            $userGroupCodes = $user->groups->pluck('code')->toArray();
            if (in_array(Permissions::WHITE_GROUP, $userGroupCodes)) {
                return true;
            }
        }
        return false;
    }

}