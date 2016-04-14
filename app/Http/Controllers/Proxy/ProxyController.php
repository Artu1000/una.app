<?php

namespace App\Http\Controllers\Proxy;

use App\Http\Controllers\Controller;

class ProxyController extends Controller
{

    /**
     * UsersController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return mixed
     */
    public function qrCodeScanCapture()
    {
        // we create the directory if it does not exists
        if(!is_dir(storage_path('app/proxy'))){
            mkdir(storage_path('app/proxy'));
        }

        // we increment the qr capture count
        $count = 0;
        if (is_file(storage_path('app/proxy/qr.json'))) {
            $qr = json_decode(file_get_contents(storage_path('app/proxy/qr.json')));
            $count = $qr->qr_code_scan_capture_count ? $qr->qr_code_scan_capture_count : 0;
        }
        $count++;

        // we store the qr capture count into the json file
        file_put_contents(storage_path('app/proxy/qr.json'), json_encode([
            'qr_code_scan_capture_count' => $count
        ]));

        // we redirect to the home
        return redirect()->route('home');
    }
}
