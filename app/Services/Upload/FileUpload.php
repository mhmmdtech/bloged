<?php

namespace App\Services\Upload;

use App\Services\Image\ImageService;
use Illuminate\Support\Str;

class FileUpload
{
    public function __construct(private ImageService $imageService)
    {
        //
    }

    public function uploadMultiQualityImage($file, $path, $name)
    {
        $this->imageService->setExclusiveDirectory('images');
        $this->imageService->setImageDirectory($path);
        $this->imageService->setImageName(Str::slug($name));
        return $this->imageService->createIndexAndSave($file);
    }
}