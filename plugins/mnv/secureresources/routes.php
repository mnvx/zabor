<?php
Route::get('/storage/app/uploads/protected/{path}', function ($path) {
    // @todo: move this logic to model and access it through controller
    $whiteGroup = 'member'; // @todo use constant
    $user = Auth::getUser();
    if ($user) {
        $userGroupCodes = $user->groups->pluck('code')->toArray();
        if (in_array($whiteGroup, $userGroupCodes)) {
            $path = storage_path('app/uploads/protected/' . $path);
            $file = File::get($path);
            $type = File::mimeType($path);
            return Response::make($file, 200)
                ->header("Content-Type", $type)
                ->header("Content-Disposition", "filename=" . request()->get('filename'));
        }
    }

    return 'Has no access';

})->where('path', '.+');