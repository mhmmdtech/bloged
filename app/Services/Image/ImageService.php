<?php

namespace App\Services\Image;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageService extends ImageToolsService
{
    public function save($image)
    {
        //set image
        $this->setImage($image);
        //execute provider
        $this->provider();
        //save image
        $resizedImage = Image::make($image->getRealPath())->encode($this->getImageFormat(), 100);
        $result = Storage::put($this->getImageAddress(), $resizedImage) ? $this->getImageAddress() : false;

        return $result;
    }

    public function saveSVG($image)
    {
        //set image
        $this->setImage($image);
        //execute provider
        $this->provider();
        //save image
        $result = Storage::putFileAs($this->getFinalImageDirectory(), $this->getImage(), $this->getFinalImageName()) ? $this->getImageAddress() : false;
        return $result;
    }

    public function fitAndSave($image, $width, $height)
    {
        //set image
        $this->setImage($image);
        //execute provider
        $this->provider();
        //save image
        $resizedImage = Image::make($image->getRealPath())->fit($width, $height)->encode($this->getImageFormat(), 100);
        $result = Storage::put($this->getImageAddress(), $resizedImage) ? $this->getImageAddress() : false;

        return $result;
    }

    public function createIndexAndSave($image)
    {
        //get data from config
        $imageSizes = Config::get('image.index-image-sizes');

        //set image
        $this->setImage($image);

        //set directory
        $this->getImageDirectory() ?? $this->setImageDirectory(date('Y') . DIRECTORY_SEPARATOR . date('m') . DIRECTORY_SEPARATOR . date('d'));
        $this->setImageDirectory($this->getImageDirectory() . DIRECTORY_SEPARATOR . time());

        //set name
        $this->getImageName() ?: $this->setImageName(time());
        $imageName = $this->getImageName();

        $indexArray = [];
        foreach ($imageSizes as $sizeAlias => $imageSize) {
            //create and set this size name
            $currentImageName = $imageName . '_' . $sizeAlias;
            $this->setImageName($currentImageName);

            //execute provider
            $this->provider();

            //save image
            $resizedImage = Image::make($image->getRealPath())->fit($imageSize['width'], $imageSize['height'])->encode($this->getImageFormat(), 100);
            $result = Storage::put($this->getImageAddress(), $resizedImage) ? $this->getImageAddress() : '';

            if ($result) {
                $indexArray[$sizeAlias] = $this->getImageAddress();
            } else {
                return false;
            }
        }
        $images['directory'] = $this->getFinalImageDirectory();
        $images['defaultSize'] = Config::get('image.default-size');
        $images['sizes'] = $indexArray;

        return $images;
    }

    public function deleteImage($imagePath)
    {
        if (is_null($imagePath)) {
            return false;
        }

        if (Storage::exists($imagePath)) {
            Storage::delete($imagePath);
        }
    }

    public function deleteIndex($images)
    {
        $directory = $images['directory'];
        $this->deleteDirectoryAndFiles($directory);
    }

    public function deleteDirectoryAndFiles($directory)
    {
        if (!Storage::exists($directory)) {
            return false;
        }

        $files = Storage::allFiles($directory);
        foreach ($files as $file) {
            Storage::delete($file);
        }

        $result = Storage::deleteDirectory($directory);

        return $result;
    }
}