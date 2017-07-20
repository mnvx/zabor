<?php

namespace Mnv\SecureResources\Models;

use Illuminate\Support\Facades\Response;
use October\Rain\Support\Facades\File;

class Attachments
{
    public static function getProtectedResource($path)
    {
        $path = storage_path('app/uploads/protected/' . $path);
        $file = File::get($path);
        $type = File::mimeType($path);
        return Response::make($file, 200)
            ->header("Content-Type", $type)
            ->header("Content-Disposition", "filename=" . request()->get('filename'));
    }
}