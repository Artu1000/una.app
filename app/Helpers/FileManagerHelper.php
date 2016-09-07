<?php

namespace App\Helpers;

use App\Repositories\Libraries\LibraryFileRepositoryInterface;
use App\Repositories\Registration\RegistrationFormDownloadRepositoryInterface;
use CustomLog;
use Exception;
use File;
use InvalidArgumentException;

class FileManagerHelper
{
    
    /**
     * @param string $src_path
     * @param string $file_name
     * @param string $extension
     * @param string $storage_path
     * @param bool $remove_src
     * @return string
     */
    public function storeAndRename(string $src_path, string $file_name, string $extension, string $storage_path, $remove_src = true)
    {
        // we low case of the extension
        $extension = strtolower($extension);
        
        // we version the image name
        $file_name = $this->setFileVersion($file_name);
        
        // we store the file
        $dest_path = $storage_path . '/' . $file_name . '.' . $extension;
        if ($remove_src) {
            File::move($src_path, $dest_path);
        } else {
            File::copy($src_path, $dest_path);
        }
        
        // we return the file name
        return $file_name . '.' . $extension;
    }
    
    /**
     * @param string $file_name
     * @param string $storage_path
     * @throws Exception
     */
    public function remove(string $file_name, string $storage_path)
    {
        // if the file exists
        $path = $storage_path . '/' . $file_name;
        if (is_file($path)) {
            // we detect the file extension
            if (strpos($file_name, '.') === false) {
                throw new InvalidArgumentException('The file name ' . $file_name . ' contains no extension.');
            }
            
            // we delete the file
            if (is_file($path)) {
                unlink($path);
            }
            
            // we check that the file has really been deleted
            if (is_file($path)) {
                throw new Exception('The source file removal went wrong. The file ' . $path . 'still exists.');
            };
        } else {
            CustomLog::info('The file ' . $path . ' does not exists.');
        }
    }
    
    /**
     * @param string $public_path
     * @param string $file_name
     * @return string
     */
    public function filePath(string $public_path, string $file_name)
    {
        return asset($public_path . '/' . $file_name);
    }
    
    /**
     * @param string $file_name
     * @return string
     */
    public function setFileVersion(string $file_name)
    {
        // we set the new versioned image name
        $versioned_file_name = $file_name . '-' . mt_rand(1000000000, 9999999999);
        
        return $versioned_file_name;
    }
    
    /**
     * @param string $html
     * @return mixed|string
     */
    public function replaceLibraryFilesAliasesByRealPath(string $html)
    {
        // we get the library image repository
        $files_library_repo = app(LibraryFileRepositoryInterface::class);
        
        // we get every img node
        preg_match_all('/<a[^>]+>/i', $html, $results);
        $files_attributes = [];
        foreach ($results[0] as $key => $file_node) {
            // we get the image node attributes
            preg_match_all('/(href|title)=("[^"]*")/i', $file_node, $files_attributes[$file_node]);
            foreach ($files_attributes as $file_attributes) {
                // we get the image src value
                $src = str_replace('"', '', array_first(array_last($file_attributes)));
                // if the file doesn't exists
                if (!is_file($files_library_repo->getModel()->imagePath($src))) {
                    // we replace it by the image src
                    try {
                        $file = $files_library_repo->where('alias', $src)->first();
                        $html = str_replace($src, $files_library_repo->getModel()->imagePath($file->src), $html);
                    } catch (Exception $e) {
                        // we log the error
//                        CustomLog::info('No file alias has been found in this HTML content.');
                    }
                }
            }
        }
        
        return $html;
    }
    
    /**
     * @param string $storage_path
     * @param string $file_name
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(string $storage_path, string $file_name)
    {
        return route('file.download', [
            'path' => $storage_path . '/' . $file_name,
        ]);
    }
}