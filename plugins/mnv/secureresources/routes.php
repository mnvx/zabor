<?php

use Mnv\SecureResources\Models\Attachments;
use \Mnv\SecureResources\Models\Permissions;

Route::get('/storage/app/uploads/protected/{path}', function ($path) {
    if (Permissions::isUserInWhiteGroup()) {
        return Attachments::getProtectedResource($path);
    }
    return 'Has no access';
})->where('path', '.+');