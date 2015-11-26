<?php

namespace App\Http\Controllers\File;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FileController extends Controller
{

    /**
     * Create a new home controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return $this
     */
    public function index()
    {

    }

    public function image(Request $request)
    {
        if (!empty($size = $request->get('size'))) {
            list($name, $extension) = explode('.', $request->get('filename'));
            $filename = $name . '_' . $size . '.' . $extension;
        } else {
            $filename = $request->get('filename');
        }

        // we set the image path
        $path = $request->get('storage_path') . '/' . $filename;

        // we get the image content
        $img = \Image::make($path);

        // we return it
        return $img->response();
    }
}
