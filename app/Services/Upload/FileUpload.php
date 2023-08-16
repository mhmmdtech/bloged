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

    public function uploadWithResizingImage($file, $path, $name, $width = 400, $height = 400)
    {
        $this->imageService->setExclusiveDirectory('images');
        $this->imageService->setImageDirectory($path);
        $this->imageService->setImageName(Str::slug($name));
        return $this->imageService->fitAndSave($file, $width, $height);
    }
}