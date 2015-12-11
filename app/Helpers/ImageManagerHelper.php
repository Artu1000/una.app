<?php

namespace App\Helpers;

use Approached\LaravelImageOptimizer\ImageOptimizer;

class ImageManagerHelper
{

    /**
     * @param $src_path
     * @param $file_name
     * @param $extension
     * @param $storage_path
     * @param array $sizes
     * @param bool|true $remove_src
     * @return string
     * @throws \Exception
     */
    public function optimizeAndResize($src_path, $file_name, $extension, $storage_path, array $sizes, $remove_src = true)
    {
        // we optimize the image
        if ($remove_src) {
            $this->optimizeAndRemoveSrc($src_path, $storage_path, $file_name, $extension);
        } else {
            $this->optimize($src_path, $storage_path, $file_name, $extension);
        }

        // we resize the image
        $this->resize($file_name, $extension, $storage_path, $sizes);

        // we return the file name
        return $file_name . '.' . $extension;
    }

    /**
     * @param $file_name
     * @param $extension
     * @param $storage_path
     * @param array $sizes
     */
    public function resize($file_name, $extension, $storage_path, array $sizes)
    {
        // we resize the original image
        foreach ($sizes as $key => $size) {

            // we check that the size is not empty
            if (empty($size) && sizeof($size) !== 2) {
                throw new \InvalidArgumentException(
                    'Incorrect size format. Each given size array must contain two line : the width and the height of the image'
                );
            }

            // we set the resized file name
            $resized_file_name = $file_name . '_' . $key . '.' . $extension;

            // we get the optimized original image
            $optimized_original_image = \Image::make($storage_path . '/' . $file_name . '.' . $extension);

            // we resize the image
            switch (true) {
                case $size[0] && $size[1] :
                    $optimized_original_image->fit($size[0], $size[1], function ($constraint) {
                        $constraint->upsize();
                    });
                    break;
                case $size[0] && !$size[1] :
                case !$size[0] && $size[1] :
                    $optimized_original_image->resize($size[0], $size[1], function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    break;
            }

            $resized_image_path = $storage_path . '/' . $resized_file_name;
            $optimized_original_image->save($resized_image_path);
        }
    }

    /**
     * @param $src_path
     * @param $dest_path
     * @param $file_name
     * @param $extension
     * @throws \Exception
     */
    public function optimize($src_path, $dest_path, $file_name, $extension)
    {
        if (!is_file($src_path)) {
            throw new \InvalidArgumentException('The source image ' . $src_path . ' doesn\'t exists.');
        }

        // we optimize and overwrite the original image
        $opt = new ImageOptimizer();
        $opt->optimizeImage($src_path, $extension);

        // we save the optimized image
        $optimized_image = \Image::make($src_path);
        $optimized_image->save($dest_path . '/' . $file_name . '.' . $extension);

        // if the file is found after optimization
        if (!is_file($dest_path . '/' . $file_name . '.' . $extension)) {
            throw new \Exception('The image optimization went wrong. The file ' .
                $dest_path . '/' . $file_name . '.' . $extension . ' has not been found after treatment.');
        }
    }

    /**
     * @param $src_path
     * @param $dest_path
     * @param $file_name
     * @param $extension
     * @throws \Exception
     */
    public function optimizeAndRemoveSrc($src_path, $dest_path, $file_name, $extension)
    {
        if (!is_file($src_path)) {
            throw new \InvalidArgumentException('The source image ' . $src_path . ' doesn\'t exists.');
        }

        // we optimize the image
        $this->optimize($src_path, $dest_path, $file_name, $extension);

        // we delete the source image we were working on
        unlink($src_path);

        // we check that the source file has really been deleted
        if (is_file($src_path)) {
            throw new \Exception('The source image removal went wrong. The file ' .
                $src_path . 'still exists after removal.');
        };
    }

    public function remove($file_name, $storage_path, array $sizes)
    {
        // we get the file name and its extension
        list($file_name, $extension) = explode('.', $file_name);

        // we delete each resized image
        foreach ($sizes as $key => $size) {
            $path = $storage_path . '/' . $file_name . '_' . $key . '.' . $extension;
            // we tcheck if the path exists
            if (is_file($path)) {
                unlink($path);
                // we check that the image file has really been deleted
                if (is_file($path)) {
                    throw new \Exception('The source image removal went wrong. The file ' .
                        $path . 'still exists after removal.');
                };
            }
        }

        // we delete the main image file
        $path = $storage_path . '/' . $file_name . '.' . $extension;
        unlink($path);
        // we check that the image file has really been deleted
        if (is_file($path)) {
            throw new \Exception('The source image removal went wrong. The file ' .
                $path . 'still exists after removal.');
        };
    }
}