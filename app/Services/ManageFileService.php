<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\File;

class ManageFileService
{

    static function uploadFile($file, $foldarName, $path = null)
    {
        if ($file !== Null) {
            $newpath = $file->store($foldarName, 'public');
            if ($path != null) {
                self::deleteFile($path);
            }
            return $newpath;
        } else {
            return $path;
        }
    }
    static function deleteFile($path)
    {
        if (file_exists(public_path($path))) {
            File::delete(public_path($path));
        }
    }

    static function getFile($path)
    {
        try {
            return response()->file(public_path($path));
        } catch (Exception $e) {
            return $e;
        }
    }
}
