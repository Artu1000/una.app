<?php

namespace App\Http\Controllers\File;

use App\Http\Controllers\Controller;
use App\Repositories\Registration\RegistrationFormDownloadRepositoryInterface;
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
        //
    }
    
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(Request $request)
    {
        // we register the download
        if(strpos($request->path, 'registration-form') !== false){
            app(RegistrationFormDownloadRepositoryInterface::class)->create([]);
        }
        
        return response()->download(
            $request->path
        );
    }
}
