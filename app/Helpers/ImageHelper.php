<?php

namespace App\Helpers;

use Approached\LaravelImageOptimizer\ImageOptimizer;

class ImageHelper
{
    public function resize($image, $file_name, $storage_folder, Array $sizes)
    {
        // we optimize and save the original image
        $optimized_image_name = $this->optimize($image, $storage_folder, $file_name);

        // we get the image extension
        $extension = $image->getClientOriginalExtension();

        // we resize the original image
        foreach ($sizes as $size) {
            // we set the resized file name
            $resized_file_name = $file_name . '_' . implode('_', $size) . '.' . $extension;

            // we get the optimized original image
            $optimized_original_image = \Image::make(
                storage_path('app/' . $storage_folder . '/' . $optimized_image_name)
            );

            // we resize the image
            $optimized_original_image->fit($size[0], $size[1]);
            $resized_image_path = storage_path('app/' . $storage_folder . '/' . $resized_file_name);
            $optimized_original_image->save($resized_image_path);
        }

        return $optimized_image_name;
    }

    public function optimize($image, $storage_folder, $file_name)
    {
        // we get the image path
        $original_path = $image->getRealPath();

        // we get the image extension
        $extension = $image->getClientOriginalExtension();

        // we optimize and overwrite the original image
        $opt = new ImageOptimizer();
        $opt->optimizeImage($original_path, $extension);

        // we save the optimized image
        $optimized_image = \Image::make($original_path);
        $storage_path = storage_path('app/' . $storage_folder . '/' . $file_name . '.' . $extension);
        $optimized_image->save($storage_path);

        // we delete the original image we were working on
        if (!unlink($original_path)) {
            throw new \Exception('An error occurred during the temp image removal.');
        };

        return $file_name . '.' . $extension;
    }
}