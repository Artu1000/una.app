<?php

namespace App\Http\Controllers\File;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

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

        if(!empty($request->get('size'))){
            list($name, $extension) = explode('.', $request->get('filename'));
            $filename = $name . '_' . implode('_', $request->get('size')) . '.' . $extension;
        } else {
            $filename = $request->get('filename');
        }

        // we set the image path
        $path = storage_path('app/' . $request->get('folder')) . '/' . $filename;
        // we get the image content
        $img =  \Image::make($path);
        // we return it
        return $img->response();
    }
}
