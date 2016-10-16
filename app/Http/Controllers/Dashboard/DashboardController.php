<?php

namespace App\Http\Controllers\Dashboard;

use Analytics;
use App\Http\Controllers\Controller;
use App\Repositories\QrCodeScan\QrCodeScanRepositoryInterface;
use App\Repositories\Registration\RegistrationFormDownloadRepositoryInterface;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use InputSanitizer;
use JavaScript;
use Permission;
use Spatie\Analytics\Period;
use Validation;

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
        
        $now = Carbon::now();
        $pre_formatted_periods = [];
        // 7 last days
        $referrer_start = clone $now;
        $referrer_end = clone $now;
        $pre_formatted_periods['last_7_days'] = [
            'start_date' => $referrer_start->subDays(7)->startOfDay()->format('d/m/Y H:i'),
            'end_date'   => $referrer_end->subDays(1)->endOfDay()->format('d/m/Y H:i'),
        ];
        // last week
        $referrer_start = clone $now;
        $referrer_end = clone $now;
        $pre_formatted_periods['last_complete_week'] = [
            'start_date' => $referrer_start->subWeek()->startOfWeek()->startOfDay()->format('d/m/Y H:i'),
            'end_date'   => $referrer_end->subWeek()->endOfWeek()->endOfDay()->format('d/m/Y H:i'),
        ];
        // 30 last days
        $referrer_start = clone $now;
        $referrer_end = clone $now;
        $pre_formatted_periods['last_30_days'] = [
            'start_date' => $referrer_start->subDays(30)->startOfDay()->format('d/m/Y H:i'),
            'end_date'   => $referrer_end->subDays(1)->endOfDay()->format('d/m/Y H:i'),
        ];
        // last month
        $referrer_start = clone $now;
        $referrer_end = clone $now;
        $pre_formatted_periods['last_complete_month'] = [
            'start_date' => $referrer_start->subMonth()->startOfMonth()->startOfDay()->format('d/m/Y H:i'),
            'end_date'   => $referrer_end->subMonth()->endOfMonth()->endOfDay()->format('d/m/Y H:i'),
        ];
        // 90 last days
        $referrer_start = clone $now;
        $referrer_end = clone $now;
        $pre_formatted_periods['last_90_days'] = [
            'start_date' => $referrer_start->subDays(90)->startOfDay()->format('d/m/Y H:i'),
            'end_date'   => $referrer_end->subDays(1)->endOfDay()->format('d/m/Y H:i'),
        ];
        // 3 last months
        $referrer_start = clone $now;
        $referrer_end = clone $now;
        $pre_formatted_periods['last_3_complete_months'] = [
            'start_date' => $referrer_start->subMonths(3)->startOfMonth()->startOfDay()->format('d/m/Y H:i'),
            'end_date'   => $referrer_end->subMonth()->endOfMonth()->endOfDay()->format('d/m/Y H:i'),
        ];
        // 180 last days
        $referrer_start = clone $now;
        $referrer_end = clone $now;
        $pre_formatted_periods['last_180_days'] = [
            'start_date' => $referrer_start->subDays(180)->startOfDay()->format('d/m/Y H:i'),
            'end_date'   => $referrer_end->subDays(1)->endOfDay()->format('d/m/Y H:i'),
        ];
        // 6 last months
        $referrer_start = clone $now;
        $referrer_end = clone $now;
        $pre_formatted_periods['last_6_complete_months'] = [
            'start_date' => $referrer_start->subMonths(6)->startOfMonth()->startOfDay()->format('d/m/Y H:i'),
            'end_date'   => $referrer_end->subMonth()->endOfMonth()->endOfDay()->format('d/m/Y H:i'),
        ];
        // this year
        $referrer_start = clone $now;
        $referrer_end = clone $now;
        $pre_formatted_periods['this_year'] = [
            'start_date' => $referrer_start->startOfYear()->startOfDay()->format('d/m/Y H:i'),
            'end_date'   => $referrer_end->endOfDay()->format('d/m/Y H:i'),
        ];
        // last year
        $referrer_start = clone $now;
        $referrer_end = clone $now;
        $pre_formatted_periods['last_year'] = [
            'start_date' => $referrer_start->subYear()->startOfYear()->startOfDay()->format('d/m/Y H:i'),
            'end_date'   => $referrer_end->subYear()->endOfYear()->endOfDay()->format('d/m/Y H:i'),
        ];
        
        Javascript::put([
            'pre_formatted_periods' => $pre_formatted_periods,
            'trans'                 => [
                'visitors'       => trans('dashboard.statistics.label.visitors'),
                'page_views'     => trans('dashboard.statistics.label.page_views'),
                'pages'          => trans('dashboard.statistics.label.pages'),
                'url'            => trans('dashboard.statistics.label.url'),
                'views'          => trans('dashboard.statistics.label.views'),
                'form_downloads' => trans('dashboard.statistics.label.form_downloads'),
                'qr_code_scans'  => trans('dashboard.statistics.label.qr_code_scans'),
            ],
        ]);
        
        // prepare data for the view
        $data = [
            'seo_meta'              => $this->seo_meta,
            'pre_formatted_periods' => $pre_formatted_periods,
        ];
        
        // return the view with data
        return view('pages.back.dashboard')->with($data);
    }
    
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatistics(Request $request)
    {
        // we check the current user permission
        if ($permission_denied = Permission::hasPermissionJson('dashboard.statistics')) {
            return response()->json([
                'message' => [$permission_denied],
            ], 401);
        }
        
        // we replace the FR dates by database dates
        $request->merge([
            'start_date' => Carbon::createFromFormat('d/m/Y H:i', $request->start_date)->format('Y-m-d H:i:s'),
            'end_date'   => Carbon::createFromFormat('d/m/Y H:i', $request->end_date)->format('Y-m-d H:i:s'),
        ]);
        
        // we sanitize the entries
        $request->replace(InputSanitizer::sanitize($request->all()));
        
        // we check inputs validity
        $rules = [
            'start_date' => 'required|date|date_format:Y-m-d H:i:s',
            'end_date'   => 'required|date|date_format:Y-m-d H:i:s',
        ];
        if (is_array($errors = Validation::check($request->all(), $rules, true))) {
            return response([
                'message' => $errors,
            ], 401);
        }
        
        // we set the google analytics period
        $start = new DateTime(Carbon::createFromFormat('Y-m-d H:i:s', $request->start_date));
        $end = new DateTime(Carbon::createFromFormat('Y-m-d H:i:s', $request->end_date));
        $period = Period::create($start, $end);
        
        $statistics = [];
        
        // we get the visitprs and page views data
        $visitors_and_page_views = Analytics::fetchVisitorsAndPageViews($period);
        $visitors_and_page_views_sorted_by_date = [];
        $formatted_visitors_and_page_views_data = [];
        foreach ($visitors_and_page_views as $detail) {
            $visitors_and_page_views_sorted_by_date[$detail['date']->format('d/m/Y')][] = $detail;
        }
        $dates = array_keys($visitors_and_page_views_sorted_by_date);
        foreach ($dates as $date) {
            $visitors = 0;
            $page_views = 0;
            foreach ($visitors_and_page_views_sorted_by_date[$date] as $data) {
                $visitors += $data['visitors'];
                $page_views += $data['pageViews'];
            }
            $formatted_visitors_and_page_views_data[$date] = [
                'date'       => $date,
                'visitors'   => $visitors,
                'page_views' => $page_views,
            ];
        }
        $statistics['visitors_and_page_views'] = $formatted_visitors_and_page_views_data;
        // we get the most visited pages
        $statistics['most_visited_pages'] = Analytics::fetchMostVisitedPages($period);
        // we get the top referrers
        $statistics['top_referrers'] = Analytics::fetchTopReferrers($period, 10);
        // we get the top browsers
        $statistics['top_browsers'] = Analytics::fetchTopBrowsers($period);
        
        // we get the other stats
        $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->start_date);
        $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->end_date);
        $registration_form_download_repo = app(RegistrationFormDownloadRepositoryInterface::class);
        $qr_code_repository = app(QrCodeScanRepositoryInterface::class);
        $other_stats = [];
        $referrer = clone $start_date;
        do {
            $other_stats[] = [
                'date'           => $referrer->format('d/m/Y'),
                'form_downloads' => $registration_form_download_repo->getModel()->whereBetween('created_at', [
                    $referrer->startOfDay()->format('Y-m-d H:i:s'),
                    $referrer->endOfDay()->format('Y-m-d H:i:s'),
                ])->count(),
                'qr_code_scans'  => $qr_code_repository->getModel()->whereBetween('created_at', [
                    $referrer->startOfDay()->format('Y-m-d H:i:s'),
                    $referrer->endOfDay()->format('Y-m-d H:i:s'),
                ])->count(),
            ];
            $stop = $referrer->lt($end_date);
            $referrer->addDay();
        } while ($stop);
        $statistics['other_stats'] = $other_stats;
        
        return response()->json($statistics, 200);
    }
}
