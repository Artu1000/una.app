<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Repositories\QrCodeScan\QrCodeScanRepositoryInterface;
use Carbon\Carbon;

class DashboardController extends Controller
{
    
    /**
     * DashboardController constructor.
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
        
        // SEO Meta settings
        $this->seo_meta['page_title'] = trans('seo.back.dashboard.index');
        
        $qr_code_repository = app(QrCodeScanRepositoryInterface::class);
        
        $qr_code_scans = [
            'today'        => [
                'start_date' => Carbon::now()->startOfDay()->format('d/m/Y'),
                'end_date'   => Carbon::now()->endOfDay()->format('d/m/Y'),
                'count'      => $qr_code_repository
                    ->where('created_at', '>', Carbon::now()->startOfDay())
                    ->where('created_at', '<', Carbon::now()->endOfDay())
                    ->count(),
            ],
            'yesterday'    => [
                'start_date' => Carbon::now()->subDays(1)->startOfDay()->format('d/m/Y'),
                'end_date'   => Carbon::now()->subDays(1)->endOfDay()->format('d/m/Y'),
                'count'      => $qr_code_repository
                    ->where('created_at', '>', Carbon::now()->subDays(1)->startOfDay())
                    ->where('created_at', '<', Carbon::now()->subDays(1)->endOfDay())
                    ->count(),
            ],
            'last_7_days'  => [
                'start_date' => Carbon::now()->subDays(8)->startOfDay()->format('d/m/Y'),
                'end_date'   => Carbon::now()->subDays(1)->endOfDay()->format('d/m/Y'),
                'count'      => $qr_code_repository
                    ->where('created_at', '>', Carbon::now()->subDays(8)->startOfDay())
                    ->where('created_at', '<', Carbon::now()->subDays(1)->endOfDay())
                    ->count(),
            ],
            'last_week'    => [
                'start_date' => Carbon::now()->subWeeks(1)->startOfWeek()->startOfDay()->format('d/m/Y'),
                'end_date'   => Carbon::now()->subWeeks(1)->endOfWeek()->endOfDay()->format('d/m/Y'),
                'count'      => $qr_code_repository
                    ->where('created_at', '>', Carbon::now()->subWeeks(1)->startOfWeek()->startOfDay())
                    ->where('created_at', '<', Carbon::now()->subWeeks(1)->endOfWeek()->endOfDay())
                    ->count(),
            ],
            'last_30_days' => [
                'start_date' => Carbon::now()->subDays(31)->startOfDay()->format('d/m/Y'),
                'end_date'   => Carbon::now()->subDays(1)->endOfDay()->format('d/m/Y'),
                'count'      => $qr_code_repository
                    ->where('created_at', '>', Carbon::now()->subDays(31)->startOfDay())
                    ->where('created_at', '<', Carbon::now()->subDays(1)->endOfDay())
                    ->count(),
            ],
            'last_month'   => [
                'start_date' => Carbon::now()->subMonth()->startOfMonth()->format('d/m/Y'),
                'end_date'   => Carbon::now()->subMonth()->endOfMonth()->format('d/m/Y'),
                'count'      => $qr_code_repository->where('created_at', '>', Carbon::now()->subMonth()->startOfMonth())
                    ->where('created_at', '<', Carbon::now()->subMonth()->endOfMonth())
                    ->count(),
            ],
            'all'          => [
                'start_date' => Carbon::createFromFormat(
                    'Y-m-d H:i:s',
                    $qr_code_repository->orderBy('created_at', 'desc')->first()->created_at
                )->format('d/m/Y'),
                'end_date'   => Carbon::createFromFormat(
                    'Y-m-d H:i:s',
                    $qr_code_repository->orderBy('created_at', 'asc')->first()->created_at
                )->format('d/m/Y'),
                'count'      => $qr_code_repository->count(),
            ],
        ];
        
        // prepare data for the view
        $data = [
            'seo_meta'      => $this->seo_meta,
            'qr_code_scans' => $qr_code_scans,
        ];
        
        // return the view with data
        return view('pages.back.dashboard')->with($data);
    }
    
}
