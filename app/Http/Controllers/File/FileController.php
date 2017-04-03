<?php

namespace App\Http\Controllers\File;

use App\Http\Controllers\Controller;
use App\Repositories\Registration\RegistrationFormDownloadRepositoryInterface;
use CustomLog;
use Exception;
use Illuminate\Http\Request;
use Modal;
use Route;

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
        $registration_form_download = null;
        if(strpos($request->path, 'registration-form') !== false){
            $registration_form_download = app(RegistrationFormDownloadRepositoryInterface::class)->create([]);
        }
        
        // we transform absolute path in relative path
        $path = str_replace(url('/') .  '/', '', $request->path);
        
        try {
            // we trigger the download of the the file
            // attention : must be a relative path
            return response()->download($path);
        } catch(Exception $e) {
            // we log the error
            CustomLog::error($e);
            
            // we notify the current user
            Modal::alert([
                trans('global.file.download.error'),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email'),]),
            ], 'error');
            
            // we delete the download count
            if($registration_form_download) $registration_form_download->delete();
            
            return redirect()->back();
        }
    }
}
