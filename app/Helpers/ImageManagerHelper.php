<?php

namespace App\Helpers;

use App\Repositories\Libraries\LibraryImageRepositoryInterface;
use Approached\LaravelImageOptimizer\ImageOptimizer;
use CustomLog;
use Exception;
use Image;
use InvalidArgumentException;

class ImageManagerHelper
{
    /**
     * @param string $src_path
     * @param string $file_name
     * @param string $extension
     * @param string $storage_path
     * @param array $sizes
     * @param bool $remove_src
     * @return string
     * @throws Exception
     */
    public function optimizeAndResize(string $src_path, string $file_name, string $extension, string $storage_path, array $sizes, bool $remove_src = true)
    {
        // we low case of the extension
        $extension = strtolower($extension);

        // we version the image name
        $file_name = $this->setImageVersion($file_name);

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
     * @param string $file_name
     * @param string $extension
     * @param string $storage_path
     * @param array $sizes
     */
    public function resize(string $file_name, string $extension, string $storage_path, array $sizes)
    {
        // we low case of the extension
        $extension = strtolower($extension);

        // we resize the original image
        foreach ($sizes as $key => $size) {

            // we check that the size is not empty
            if (empty($size) && sizeof($size) !== 2) {
                throw new InvalidArgumentException(
                    'Incorrect size format. Each given size array must contain two line : width and height (one of them can be null)'
                );
            }

            // we set the resized file name
            $resized_file_name = str_slug($file_name . '-' . $key) . '.' . $extension;

            // we get the optimized original image
            $optimized_original_image = Image::make($storage_path . '/' . str_slug($file_name) . '.' . $extension);

            // we resize the image
            switch (true) {
                // the width and the height are given
                case isset($size[0]) && isset($size[1]) :
                    $optimized_original_image->fit($size[0], $size[1], function ($constraint) {
                        $constraint->upsize();
                    });
                    break;
                // only width or height is given
                case $size[0] && !isset($size[1]) :
                case !isset($size[0]) && $size[1] :
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
     * @param string $src_path
     * @param string $dest_path
     * @param string $file_name
     * @param string $extension
     * @throws Exception
     */
    public function optimize(string $src_path, string $dest_path, string $file_name, string $extension)
    {
        if (!is_file($src_path)) {
            throw new InvalidArgumentException('The source image ' . $src_path . ' does not exists.');
        }

        // wet low case of the extension
        $extension = strtolower($extension);

        // we slug the file name
        $file_name = str_slug($file_name);

        // we optimize and overwrite the original image
        $opt = new ImageOptimizer();
        $opt->optimizeImage($src_path, $extension);

        // we save the optimized image
        $optimized_image = Image::make($src_path);

        // we save the optimized image in the dest folder
        $optimized_image->save($dest_path . '/' . $file_name . '.' . $extension);

        // we check if the optimized image is lighter than the original
        if (Image::make($src_path)->filesize() < Image::make($dest_path . '/' . $file_name . '.' . $extension)->filesize()) {
            // if not, we replace the optimized image by the original (renamed)
            copy($src_path, $dest_path . '/' . $file_name . '.' . $extension);
        }

        // if the file is found after optimization
        if (!is_file($dest_path . '/' . $file_name . '.' . $extension)) {
            throw new Exception('The image optimization went wrong. The file ' .
                $dest_path . '/' . $file_name . '.' . $extension . ' has not been found after treatment.');
        }
    }

    /**
     * @param string $src_path
     * @param string $dest_path
     * @param string $file_name
     * @param string $extension
     * @throws Exception
     */
    public function optimizeAndRemoveSrc(string $src_path, string $dest_path, string $file_name, string $extension)
    {
        if (!is_file($src_path)) {
            throw new InvalidArgumentException('The source image ' . $src_path . ' doesn\'t exists.');
        }

        // wet low case of the extension
        $extension = strtolower($extension);

        // we slug the file name
        $file_name = str_slug($file_name);

        // we optimize the image
        $this->optimize($src_path, $dest_path, $file_name, $extension);

        // we delete the source image we were working on
        unlink($src_path);

        // we check that the source file has really been deleted
        if (is_file($src_path)) {
            throw new Exception('The source image removal went wrong. The file ' .
                $src_path . 'still exists after removal.');
        };
    }

    /**
     * @param string $file_name
     * @param string $storage_path
     * @param array $sizes
     * @throws Exception
     */
    public function remove(string $file_name, string $storage_path, array $sizes)
    {

        if (strpos($file_name, '.') === false) {
            throw new InvalidArgumentException('The file name ' . $file_name . ' contains no extension.');
        }

        // we get the file name and its extension
        list($file_name, $extension) = explode('.', $file_name);

        // we delete each resized image
        foreach ($sizes as $key => $size) {
            $path = $storage_path . '/' . str_slug($file_name . '-' . $key) . '.' . $extension;
            // we tcheck if the path exists
            if (is_file($path)) {
                unlink($path);
                // we check that the image file has really been deleted
                if (is_file($path)) {
                    throw new Exception('The source image removal went wrong. The file ' .
                        $path . 'still exists after removal.');
                };
            }
        }

        // we delete the main image file
        $path = $storage_path . '/' . $file_name . '.' . $extension;
        if (is_file($path)) {
            unlink($path);
        }
        // we check that the image file has really been deleted
        if (is_file($path)) {
            throw new Exception('The source image removal went wrong. The file ' .
                $path . 'still exists after removal.');
        };
    }

    /**
     * @param string $public_path
     * @param string $file_name
     * @param string|null $key
     * @param string|null $size
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function imagePath(string $public_path, string $file_name, string $key = null, string $size = null)
    {
        // if the key / size are not given
        if (!$key || !$size) {
            // we return the original image path
            return asset($public_path . '/' . $file_name);
        }

        try {
            // we return the sized image path
            list($name, $ext) = explode('.', $file_name);

            return asset($public_path . '/' . str_slug($name . '-' . $size) . '.' . $ext);
        } catch (Exception $e) {
            // we log the error
            CustomLog::error($e);

            return 'image-error';
        }
    }

    /**
     * @param string $file_name
     * @return string
     */
    public function setImageVersion(string $file_name)
    {
        // we set the new versioned image name
        $versioned_img_name = $file_name . '-' . mt_rand(1000000000, 9999999999);

        return $versioned_img_name;
    }
    
    /**
     * @param string $html
     * @return mixed|string
     */
    public function replaceLibraryImagesAliasesByRealPath(string $html)
    {
        // we get the library image repository
        $images_library_repo = app(LibraryImageRepositoryInterface::class);
        
        // we get every img node
        preg_match_all('/<img[^>]+>/i', $html, $results);
        $images_attributes = [];
        foreach ($results[0] as $key => $image_node) {
            // we get the image node attributes
            preg_match_all('/(alt|title|src)=("[^"]*")/i', $image_node, $images_attributes[$image_node]);
            foreach ($images_attributes as $image_attributes) {
                // we get the image src value
                $src = str_replace('"', '', array_first(array_last($image_attributes)));
                // if the file doesn't exists
                if (!is_file($images_library_repo->getModel()->imagePath($src))) {
                    // we replace it by the image src
                    try {
                        $image = $images_library_repo->where('alias', $src)->first();
                        $html = str_replace($src, $images_library_repo->getModel()->imagePath($image->src), $html);
                    } catch (Exception $e) {
                        // we log the error
                        CustomLog::info('No image alias has been found in this HTML content.');
                    }
                }
            }
        }
        
        return $html;
    }
}