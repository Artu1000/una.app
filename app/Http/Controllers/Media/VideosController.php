<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Repositories\Media\VideoRepositoryInterface;
use Carbon\Carbon;
use CustomLog;
use Exception;
use Illuminate\Http\Request;
use ImageManager;
use InputSanitizer;
use Markdown;
use Modal;
use Permission;
use Sentinel;
use TableList;
use Validation;

class VideosController extends Controller
{
    
    /**
     * VideosController constructor.
     * @param VideoRepositoryInterface $video
     */
    public function __construct(VideoRepositoryInterface $video)
    {
        parent::__construct();
        $this->repository = $video;
    }
    
    /**
     * @return $this
     */
    public function index(Request $request)
    {
        // we get the json content
        $page = null;
        if (is_file(storage_path('app/videos/content.json'))) {
            $page = json_decode(file_get_contents(storage_path('app/videos/content.json')));
        }
        
        // we parse the markdown content
        $title = isset($page->title) ? $page->title : null;
        $description = isset($page->description) ? Markdown::parse($page->description) : null;
        $background_image = $page->background_image ? ImageManager::imagePath(config('image.photos.public_path'), $page->background_image, 'background_image', 767) : null;
        
        // SEO Meta settings
        $this->seo_meta['page_title'] = trans('seo.front.videos.title') ? trans('seo.front.videos.title') : $title;
        $this->seo_meta['meta_desc'] = trans('seo.front.videos.description') ? trans('seo.front.videos.description') : str_limit($description, 160);
        $this->seo_meta['meta_keywords'] = trans('seo.front.videos.keywords');
        
        // og meta settings
        $this->og_meta['og:title'] = trans('seo.front.videos.title') ? trans('seo.front.videos.title') : $title;
        $this->og_meta['og:description'] = trans('seo.front.videos.description') ? trans('seo.front.videos.description') : str_limit($description, 160);
        $this->og_meta['og:type'] = 'article';
        $this->og_meta['og:url'] = route('videos.index');
        $this->og_meta['og:image'] = $background_image;
        
        // twitter meta settings
        $this->twitter_meta['twitter:title'] = trans('seo.front.videos.title') ? trans('seo.front.videos.title') : $title;
        $this->twitter_meta['twitter:description'] = trans('seo.front.videos.description') ? trans('seo.front.videos.description') : str_limit($description, 160);
        $this->twitter_meta['twitter:image'] = $background_image;
        
        // we sanitize the entries
        $request->replace(InputSanitizer::sanitize($request->all()));
        
        // we convert the fr date to database format
        if ($request->get('year')) {
            $request->merge([
                'year' => Carbon::create($request->get('year'))->format('Y'),
            ]);
        }
        
        // we check inputs validity
        $rules = [
            'year' => 'date_format:Y',
        ];
        // we check the inputs validity
        if (!Validation::check($request->all(), $rules)) {
            return redirect()->back();
        }
        
        // we get the availables years
        $years = $this->repository->getAvailableYears();
        
        // we set the year
        if ($request->year) {
            $selected_year = $request->year;
        } else {
            $selected_year = array_first($years);
        }
        
        // sort results by year
        $videos_list = $this->repository->getModel()
            ->whereBetween('date', [$selected_year . '-01-01', $selected_year . '-12-31'])
            ->where('active', true)
            ->orderBy('date', 'desc')
            ->get();
        
        // prepare data for the view
        $data = [
            'seo_meta'         => $this->seo_meta,
            'og_meta'          => $this->og_meta,
            'twitter_meta'     => $this->twitter_meta,
            'videos_list'      => $videos_list,
            'title'            => isset($page->title) ? $page->title : null,
            'background_image' => isset($page->background_image) ? $page->background_image : null,
            'description'      => $description,
            'years'            => $years,
            'selected_year'    => $selected_year,
            'css'              => elixir('css/app.videos.css'),
            'js'               => elixir('js/app.videos.js'),
        ];
        
        // return the view with data
        return view('pages.front.videos-list')->with($data);
    }
    
    /**
     * @param Request $request
     * @return $this
     */
    public function pageEdit(Request $request)
    {
        // we check the current user permission
        if (!Permission::hasPermission('videos.page.view')) {
            return redirect()->route('dashboard.index');
        }
        
        // SEO Meta settings
        $this->seo_meta['page_title'] = trans('seo.back.videos.page.edit');
        
        // we define the table list columns
        $columns = [
            [
                'title' => trans('videos.page.label.cover'),
                'key'   => 'cover',
                'image' => [
                    'storage_path' => $this->repository->getModel()->storagePath(),
                    'size'         => [
                        'thumbnail' => 'admin',
                        'detail'    => 'front',
                    ],
                ],
            ],
            [
                'title'   => trans('videos.page.label.title'),
                'key'     => 'title',
                'sort_by' => 'videos.title',
            ],
            [
                'title'   => trans('videos.page.label.link'),
                'key'     => 'link',
                'sort_by' => 'videos.link',
            ],
            [
                'title'           => trans('videos.page.label.date'),
                'key'             => 'date',
                'sort_by'         => 'videos.date',
                'sort_by_default' => 'desc',
                'date'            => 'd/m/Y',
            ],
            [
                'title'    => trans('videos.page.label.activation'),
                'key'      => 'active',
                'activate' => [
                    'route'  => 'videos.activate',
                    'params' => [],
                ],
            ],
        ];
        
        // we set the routes used in the table list
        $routes = [
            'index'   => [
                'route'  => 'videos.page.edit',
                'params' => [],
            ],
            'create'  => [
                'route'  => 'videos.create',
                'params' => [],
            ],
            'edit'    => [
                'route'  => 'videos.edit',
                'params' => [],
            ],
            'destroy' => [
                'route'  => 'videos.destroy',
                'params' => [],
            ],
        ];
        
        // we instantiate the query
        $query = $this->repository->getModel()->query();
        
        // we prepare the confirm config
        $confirm_config = [
            'action'     => trans('videos.page.action.delete'),
            'attributes' => ['title'],
        ];
        
        // we prepare the search config
        $search_config = [
            [
                'key'      => trans('videos.page.label.title'),
                'database' => 'videos.title',
            ],
        ];
        
        // we enable the lines choice
        $enable_lines_choice = true;
        
        // we format the data for the needs of the view
        $tableListData = TableList::prepare(
            $query,
            $request,
            $columns,
            $routes,
            $confirm_config,
            $search_config,
            $enable_lines_choice
        );
        
        // we get the json page content
        $page = null;
        if (is_file(storage_path('app/videos/content.json'))) {
            $page = json_decode(file_get_contents(storage_path('app/videos/content.json')));
        }
        
        // prepare data for the view
        $data = [
            'title'            => isset($page->title) ? $page->title : null,
            'description'      => isset($page->description) ? $page->description : null,
            'background_image' => isset($page->background_image) ? $page->background_image : null,
            'tableListData'    => $tableListData,
            'seo_meta'         => $this->seo_meta,
        ];
        
        // return the view with data
        return view('pages.back.videos-page-edit')->with($data);
    }
    
    /**
     * @param Request $request
     * @return mixed
     */
    public function pageUpdate(Request $request)
    {
        // we check the current user permission
        if (!Permission::hasPermission('videos.page.update')) {
            // we redirect the current user to the videos page view if he has the required permission
            if (Sentinel::getUser()->hasAccess('videos.page.view')) {
                return redirect()->route('videos.page.edit');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }
        
        // we get the json videos content
        $page = null;
        if (is_file(storage_path('app/videos/content.json'))) {
            $page = json_decode(file_get_contents(storage_path('app/videos/content.json')));
        }
        
        // if the active field is not given, we set it to false
        $request->merge(['remove_background_image' => $request->get('remove_background_image', false)]);
        
        // we sanitize the entries
        $request->replace(InputSanitizer::sanitize($request->all()));
        
        // we check inputs validity
        $rules = [
            'title'                   => 'required|string',
            'description'             => 'string',
            'background_image'        => 'image|mimes:jpg,jpeg|image_size:>=2560,>=1440',
            'remove_background_image' => 'required|boolean',
        ];
        // we check the inputs validity
        if (!Validation::check($request->all(), $rules)) {
            // we flash the request
            $request->flashExcept('background_image');
            
            return redirect()->back();
        }
        
        try {
            $inputs = $request->except('_token', '_method', 'background_image', 'remove_background_image');
            
            // we store the background image file
            if ($background_image = $request->file('background_image')) {
                // we optimize, resize and save the image
                $file_name = ImageManager::storeResizeAndRename(
                    $background_image->getRealPath(),
                    config('image.videos.background_image.name'),
                    $background_image->getClientOriginalExtension(),
                    config('image.videos.storage_path'),
                    config('image.videos.background_image.sizes')
                );
                // we set the file name
                $inputs['background_image'] = $file_name;
            } elseif ($request->get('remove_background_image')) {
                // we remove the background image
                if (isset($page->background_image)) {
                    ImageManager::remove(
                        $page->background_image,
                        config('image.videos.storage_path'),
                        config('image.videos.background_image.sizes')
                    );
                }
                $inputs['background_image'] = null;
            } else {
                $inputs['background_image'] = isset($page->background_image) ? $page->background_image : null;
            }
            
            // we store the content into a json file
            file_put_contents(
                storage_path('app/videos/content.json'),
                json_encode($inputs)
            );
            
            Modal::alert([
                trans('videos.message.content.update.success', ['title' => $request->get('title')]),
            ], 'success');
            
            return redirect()->back();
        } catch (Exception $e) {
            
            // we flash the request
            $request->flashExcept('background_image');
            
            // we log the error
            CustomLog::error($e);
            
            // we notify the current user
            Modal::alert([
                trans('videos.message.content.update.failure', ['title' => isset($page->title) ? $page->title : null]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');
            
            return redirect()->back();
        }
    }
    
    /**
     * @return $this
     */
    public function create()
    {
        // we check the current user permission
        if (!Permission::hasPermission('videos.create')) {
            // we redirect the current user to the videos page edit if he has the required permission
            if (Sentinel::getUser()->hasAccess('videos.page.view')) {
                return redirect()->route('videos.page.edit');
            } else {
                // or we redirect the current user to the dashboard
                return redirect()->route('dashboard.index');
            }
        }
        
        // SEO Meta settings
        $this->seo_meta['page_title'] = trans('seo.back.videos.create');
        
        // prepare data for the view
        $data = [
            'seo_meta' => $this->seo_meta,
        ];
        
        // return the view with data
        return view('pages.back.videos-edit')->with($data);
    }
    
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        // we check the current user permission
        if (!Permission::hasPermission('videos.create')) {
            // we redirect the current user to the videos list if he has the required permission
            if (Sentinel::getUser()->hasAccess('videos.page.view')) {
                return redirect()->route('videos.page.edit');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }
        
        // if the active field is not given, we set it to false
        $request->merge(['active' => $request->get('active', false)]);
        
        // we sanitize the entries
        $request->replace(InputSanitizer::sanitize($request->all()));
        
        // we convert the fr date to database format
        if ($request->get('date')) {
            $request->merge([
                'date' => Carbon::createFromFormat('d/m/Y', $request->get('date'))->format('Y-m-d'),
            ]);
        }
        
        // we check inputs validity
        $rules = [
            'cover'  => 'required|image|mimes:jpg,jpeg,png|image_size:>=220,>=220',
            'title'  => 'required|string',
            'link'   => 'required|url',
            'date'   => 'required|date_format:Y-m-d',
            'active' => 'required|boolean',
        ];
        // we check the inputs validity
        if (!Validation::check($request->all(), $rules)) {
            // we flash the request
            $request->flashExcept('cover');
            
            return redirect()->back();
        }
        
        try {
            // we create the video
            $video = $this->repository->create($request->except('_token'));
            
            // we store the image
            if ($img = $request->file('cover')) {
                // we optimize, resize and save the image
                $file_name = ImageManager::storeResizeAndRename(
                    $img->getRealPath(),
                    $video->imageName('cover'),
                    $img->getClientOriginalExtension(),
                    $video->storagePath(),
                    $video->availableSizes('cover')
                );
                // we update the model with the image name
                $video->cover = $file_name;
                $video->save();
            }
            
            // we notify the current user
            Modal::alert([
                trans('videos.message.create.success', ['video' => $video->title]),
            ], 'success');
            
            return redirect(route('videos.page.edit'));
        } catch (Exception $e) {
            // we flash the request
            $request->flashExcept('cover');
            
            // we log the error
            CustomLog::error($e);
            
            // we notify the current user
            Modal::alert([
                trans('videos.message.create.failure', ['video' => $request->title]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email'),]),
            ], 'error');
            
            return redirect()->back();
        }
    }
    
    /**
     * @param $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        // we check the current user permission
        if (!Permission::hasPermission('videos.view')) {
            // we redirect the current user to the videos list if he has the required permission
            if (Sentinel::getUser()->hasAccess('videos.page.view')) {
                return redirect()->route('videos.page.edit');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }
        
        // we get the video
        try {
            $video = $this->repository->find($id);
        } catch (Exception $e) {
            // we notify the current user
            Modal::alert([
                trans('videos.message.find.failure', ['id' => $id]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');
            
            return redirect()->back();
        }
        
        // SEO Meta settings
        $this->seo_meta['page_title'] = trans('seo.back.videos.edit');
        
        // we prepare the data for breadcrumbs
        $breadcrumbs_data = [
            'video' => $video,
        ];
        
        // we format the date in correct format
        $video->date = Carbon::createFromFormat('Y-m-d', $video->date)->format('d/m/Y');
        
        // prepare data for the view
        $data = [
            'seo_meta'         => $this->seo_meta,
            'video'            => $video,
            'breadcrumbs_data' => $breadcrumbs_data,
        ];
        
        // return the view with data
        return view('pages.back.videos-edit')->with($data);
    }
    
    /**
     * @param $îd
     * @param Request $request
     * @return mixed
     */
    public function update($id, Request $request)
    {
        // we get the video
        try {
            $video = $this->repository->find($id);
        } catch (Exception $e) {
            // we flash the request
            $request->flashExcept('cover');
            
            // we notify the current user
            Modal::alert([
                trans('videos.message.find.failure', ['id' => $id]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');
            
            return redirect()->back();
        }
        
        // we check the current user permission
        if (!Permission::hasPermission('videos.update')) {
            // we redirect the current user to the permissions list if he has the required permission
            if (Sentinel::getUser()->hasAccess('videos.view')) {
                return redirect()->route('videos.edit', ['id' => $id]);
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }
        
        // if the active field is not given, we set it to false
        $request->merge(['active' => $request->get('active', false)]);
        
        // we sanitize the entries
        $request->replace(InputSanitizer::sanitize($request->all()));
        
        // we convert the fr date to database format
        if ($request->get('date')) {
            $request->merge([
                'date' => Carbon::createFromFormat('d/m/Y', $request->get('date'))->format('Y-m-d'),
            ]);
        }
        
        // we check inputs validity
        $rules = [
            'cover'  => 'image|mimes:jpg,jpeg,png|image_size:>=220,>=220',
            'title'  => 'required|string',
            'link'   => 'required|url',
            'date'   => 'required|date_format:Y-m-d',
            'active' => 'required|boolean',
        ];
        // we check the inputs validity
        if (!Validation::check($request->all(), $rules)) {
            // we flash the request
            $request->flashExcept('cover');
            
            return redirect()->back();
        }
        
        try {
            // we update the video
            $video->update($request->except('_token', 'cover'));
            
            // we store the image
            if ($img = $request->file('cover')) {
                // we optimize, resize and save the image
                $file_name = ImageManager::storeResizeAndRename(
                    $img->getRealPath(),
                    $video->imageName('cover'),
                    $img->getClientOriginalExtension(),
                    $video->storagePath(),
                    $video->availableSizes('cover')
                );
                // we add the image name to the inputs for saving
                $video->cover = $file_name;
                $video->save();
            }
            
            // we notify the current user
            Modal::alert([
                trans('videos.message.update.success', ['video' => $video->title]),
            ], 'success');
            
            return redirect()->back();
        } catch (Exception $e) {
            // we flash the request
            $request->flashExcept('cover');
            
            // we log the error
            CustomLog::error($e);
            
            // we notify the current user
            Modal::alert([
                trans('$video.message.update.failure', ['video' => $video->title]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email'),]),
            ], 'error');
            
            return redirect()->back();
        }
    }
    
    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        // we get the video
        try {
            $video = $this->repository->find($id);
        } catch (Exception $e) {
            
            // we notify the current user
            Modal::alert([
                trans('videos.message.find.failure', ['id' => $id]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');
            
            return redirect()->back();
        }
        
        // we check the current user permission
        if (!Permission::hasPermission('videos.delete')) {
            // we redirect the current user to the videos list if he has the required permission
            if (Sentinel::getUser()->hasAccess('videos.page.view')) {
                return redirect()->route('videos.page.edit');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }
        
        try {
            // we remove the video cover
            if ($video->cover) {
                ImageManager::remove(
                    $video->cover,
                    $video->storagePath(),
                    $video->availableSizes('cover')
                );
            }
            
            // we delete the role
            $video->delete();
            
            Modal::alert([
                trans('videos.message.delete.success', ['video' => $video->title]),
            ], 'success');
            
            return redirect()->back();
        } catch (Exception $e) {
            // we log the error
            CustomLog::error($e);
            
            // we notify the current user
            Modal::alert([
                trans('videos.message.delete.failure', ['video' => $video->title]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');
            
            return redirect()->back();
        }
    }
    
    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function activate($id, Request $request)
    {
        // we get the video
        try {
            $video = $this->repository->find($id);
        } catch (Exception $e) {
            // we log the error
            CustomLog::error($e);
            
            // we notify the current user
            return response([
                'message' => [
                    trans('videos.message.find.failure', ['id' => $id]),
                    trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
                ],
            ], 401);
        }
        
        // we check the current user permission
        if ($permission_denied = Permission::hasPermissionJson('videos.update')) {
            return response([
                'active'  => $video->active,
                'message' => [$permission_denied],
            ], 401);
        }
        
        // if the active field is not given, we set it to false
        $request->merge(['active' => $request->get('active', false)]);
        
        // we sanitize the entries
        $request->replace(InputSanitizer::sanitize($request->all()));
        
        // we check inputs validity
        $rules = [
            'active' => 'required|boolean',
        ];
        if (is_array($errors = Validation::check($request->all(), $rules, true))) {
            return response([
                'active'  => $video->active,
                'message' => $errors,
            ], 401);
        }
        
        try {
            $video->active = $request->get('active');
            $video->save();
            
            return response([
                'active'  => $video->active,
                'message' => [
                    trans('videos.message.activation.success.label', ['action' => trans_choice('videos.message.activation.success.action', $video->active), 'video' => $video->title]),
                ],
            ], 200);
        } catch (Exception $e) {
            // we log the error
            CustomLog::error($e);
            
            // we notify the current user
            return response([
                'active'  => $video->fresh()->active,
                'message' => trans('videos.message.activation.failure', ['video' => $video->title]),
            ], 401);
        }
    }
    
}
