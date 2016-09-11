<?php

namespace App\Http\Controllers\File;

use App\Http\Controllers\Controller;
use App\Repositories\Registration\RegistrationFormDownloadRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Modal;

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
        $registration_form_download = null;
        
        // we register the download
        if(strpos($request->path, 'registration-form') !== false){
            $registration_form_download = app(RegistrationFormDownloadRepositoryInterface::class)->create([]);
        }
        
        try {
            return response()->download(
                $request->path
            );
        } catch(Exception $e) {
            // we notify the current user
            Modal::alert([
                trans('global.file.download.error'),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email'),]),
            ], 'error');
            
            // we delete the download count
            $registration_form_download->delete();
            
            return redirect()->back();
        }
    }
}
