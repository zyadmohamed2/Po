<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Image;

trait ImageProcessing
{
    public function get_mimes($mime)
    {
        if ($mime == 'image/jpg')
            $ext = '.jpg';
        elseif ($mime == 'image/png')
            $ext = '.png';
        elseif ($mime == 'image/gif')
            $ext = '.gif';
        elseif ($mime == 'image/jpeg')
            $ext = '.jpeg';
        elseif ($mime == 'image/webp')
            $ext = '.webp';

        return $ext;
    }

    public function saveImage($image)
    {
        if (!$image) return null;

        $img = Image::make($image);
        $ext = $this->get_mimes($img->mime());
        $imgPath = Str::random(8) . time() . $ext;
        $img->save(storage_path('app/images') . '/' . $imgPath);
        return $imgPath;
    }

    public function aspect4Size($image, $width, $height)
    {
        if (!$image) return null;

        $img = Image::make($image);
        $ext = $this->get_mimes($img->mime());
        $imgPath = Str::random(8) . time() . $ext;
        $img->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save(storage_path('app/images') . '/' . $imgPath);
        return $imgPath;
    }

    public function aspect4Height($image, $width, $height)
    {
        if (!$image) return null;

        $img = Image::make($image);
        $ext = $this->get_mimes($img->mime());
        $imgPath = Str::random(8) . time() . $ext;
        $img->resize(null, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
        if ($img->width() < $width) {
            $img->resize($width, null);
        } elseif ($img->width() > $width) {
            $img->crop($width, $height, 0, 0);
        }

        $img->save(storage_path('app/images') . '/' . $imgPath);
        return $imgPath;
    }

    public function deleteImage($image)
    {
        if ($image) {
            if (is_file(Storage::disk('images')->path($image))) {
                if (file_exists(Storage::disk('images')->path($image))) {
                    unlink(Storage::disk('images')->path($image));
                }
            }
        }
    }

    public function saveImageAndThumbnail($Thefile, $thumb = false)
    {
        $imageData = [];

        $imageData['image'] = $this->saveImage($Thefile);

        if ($thumb) {
            $imageData['thumbnailsm'] = $this->aspect4resize($Thefile, 256, 144);
            $imageData['thumbnailmd'] = $this->aspect4resize($Thefile, 426, 240);
            $imageData['thumbnailxl'] = $this->aspect4resize($Thefile, 640, 360);
        }

        return $imageData;
    }
}
