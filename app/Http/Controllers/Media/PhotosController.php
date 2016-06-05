<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Repositories\Media\PhotoRepositoryInterface;
use Carbon\Carbon;
use CustomLog;
use Entry;
use Exception;
use Illuminate\Http\Request;
use ImageManager;
use Modal;
use Parsedown;
use Permission;
use Sentinel;
use TableList;
use Validation;

class PhotosController extends Controller
{

    /**
     * PhotosController constructor.
     * @param PhotoRepositoryInterface $photo
     */
    public function __construct(PhotoRepositoryInterface $photo)
    {
        parent::__construct();
        $this->repository = $photo;
    }
    
    /**
     * @return $this
     */
    public function index(Request $request)
    {
        // we get the json content
        $page = null;
        if (is_file(storage_path('app/photos/content.json'))) {
            $page = json_decode(file_get_contents(storage_path('app/photos/content.json')));
        }

        // we parse the markdown content
        $parsedown = new Parsedown();
        $title = isset($page->title) ? $page->title : null;
        $description = isset($page->description) ? $parsedown->text($page->description) : null;

        // SEO Meta settings
        $this->seo_meta['page_title'] = trans('seo.front.photos.title') ? trans('seo.front.photos.title') : $title;
        $this->seo_meta['meta_desc'] = trans('seo.front.photos.description') ? trans('seo.front.photos.description') : str_limit($description, 160);
        $this->seo_meta['meta_keywords'] = trans('seo.front.photos.keywords');

        // og meta settings
        $this->og_meta['og:title'] = trans('seo.front.photos.title') ? trans('seo.front.photos.title') : $title;
        $this->og_meta['og:description'] = trans('seo.front.photos.description') ? trans('seo.front.photos.description') : str_limit($description, 160);
        $this->og_meta['og:type'] = 'article';
        $this->og_meta['og:url'] = route('photos.index');
        if (isset($page->background_image)) {
            $this->og_meta['og:image'] = ImageManager::imagePath(config('image.photos.public_path'), $page->background_image, 'background_image', 767);
        }

        // we sanitize the entries
        $request->replace(Entry::sanitizeAll($request->all()));

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
        $photos_list = $this->repository->getModel()
            ->whereBetween('date', [$selected_year . '-01-01', $selected_year . '-12-31'])
            ->where('active', true)
            ->orderBy('date', 'desc')
            ->get();

        // prepare data for the view
        $data = [
            'seo_meta'         => $this->seo_meta,
            'og_meta'          => $this->og_meta,
            'photos_list'      => $photos_list,
            'title'            => isset($page->title) ? $page->title : null,
            'background_image' => isset($page->background_image) ? $page->background_image : null,
            'description'      => $description,
            'years'            => $years,
            'selected_year'    => $selected_year,
            'css'              => elixir('css/app.photos.css'),
            'js'               => elixir('js/app.photos.js'),
        ];
        
        // return the view with data
        return view('pages.front.photos-list')->with($data);
    }
    
    /**
     * @param Request $request
     * @return $this
     */
    public function pageEdit(Request $request)
    {
        // we check the current user permission
        if (!Permission::hasPermission('photos.page.view')) {
            return redirect()->route('dashboard.index');
        }
        
        // SEO Meta settings
        $this->seo_meta['page_title'] = trans('seo.back.photos.page.edit');
        
        // we define the table list columns
        $columns = [
            [
                'title' => trans('photos.page.label.cover'),
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
                'title'   => trans('photos.page.label.title'),
                'key'     => 'title',
                'sort_by' => 'photos.title',
            ],
            [
                'title'   => trans('photos.page.label.link'),
                'key'     => 'link',
                'sort_by' => 'photos.link',
            ],
            [
                'title'           => trans('photos.page.label.date'),
                'key'             => 'date',
                'sort_by'         => 'photos.date',
                'sort_by_default' => 'desc',
                'date'            => 'd/m/Y',
            ],
            [
                'title'    => trans('photos.page.label.activation'),
                'key'      => 'active',
                'activate' => [
                    'route'  => 'photos.activate',
                    'params' => [],
                ],
            ],
        ];
        
        // we set the routes used in the table list
        $routes = [
            'index'   => [
                'route'  => 'photos.page.edit',
                'params' => [],
            ],
            'create'  => [
                'route'  => 'photos.create',
                'params' => [],
            ],
            'edit'    => [
                'route'  => 'photos.edit',
                'params' => [],
            ],
            'destroy' => [
                'route'  => 'photos.destroy',
                'params' => [],
            ],
        ];
        
        // we instantiate the query
        $query = $this->repository->getModel()->query();
        
        // we prepare the confirm config
        $confirm_config = [
            'action'     => trans('photos.page.action.delete'),
            'attributes' => ['title'],
        ];
        
        // we prepare the search config
        $search_config = [
            [
                'key'      => trans('photos.page.label.title'),
                'database' => 'photos.title',
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
        if (is_file(storage_path('app/photos/content.json'))) {
            $page = json_decode(file_get_contents(storage_path('app/photos/content.json')));
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
        return view('pages.back.photos-page-edit')->with($data);
    }
    
    /**
     * @param Request $request
     * @return mixed
     */
    public function pageUpdate(Request $request)
    {
        // we check the current user permission
        if (!Permission::hasPermission('photos.page.update')) {
            // we redirect the current user to the photos page view if he has the required permission
            if (Sentinel::getUser()->hasAccess('photos.page.view')) {
                return redirect()->route('photos.page.edit');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }
        
        // we get the json photos content
        $page = null;
        if (is_file(storage_path('app/photos/content.json'))) {
            $page = json_decode(file_get_contents(storage_path('app/photos/content.json')));
        }
        
        // if the active field is not given, we set it to false
        $request->merge(['remove_background_image' => $request->get('remove_background_image', false)]);
        
        // we sanitize the entries
        $request->replace(Entry::sanitizeAll($request->all()));
        
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
                $file_name = ImageManager::optimizeAndResize(
                    $background_image->getRealPath(),
                    config('image.photos.background_image.name'),
                    $background_image->getClientOriginalExtension(),
                    config('image.photos.storage_path'),
                    config('image.photos.background_image.sizes')
                );
                // we set the file name
                $inputs['background_image'] = $file_name;
            } elseif ($request->get('remove_background_image')) {
                // we remove the background image
                if (isset($page->background_image)) {
                    ImageManager::remove(
                        $page->background_image,
                        config('image.photos.storage_path'),
                        config('image.photos.background_image.sizes')
                    );
                }
                $inputs['background_image'] = null;
            } else {
                $inputs['background_image'] = isset($page->background_image) ? $page->background_image : null;
            }
            
            // we store the content into a json file
            file_put_contents(
                storage_path('app/photos/content.json'),
                json_encode($inputs)
            );
            
            Modal::alert([
                trans('photos.message.content.update.success', ['title' => $request->get('title')]),
            ], 'success');
            
            return redirect()->back();
        } catch (Exception $e) {
            
            // we flash the request
            $request->flashExcept('background_image');
            
            // we log the error
            CustomLog::error($e);
            
            // we notify the current user
            Modal::alert([
                trans('photos.message.content.update.failure', ['title' => isset($page->title) ? $page->title : null]),
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
        if (!Permission::hasPermission('photos.create')) {
            // we redirect the current user to the photos page edit if he has the required permission
            if (Sentinel::getUser()->hasAccess('photos.page.view')) {
                return redirect()->route('photos.page.edit');
            } else {
                // or we redirect the current user to the dashboard
                return redirect()->route('dashboard.index');
            }
        }
        
        // SEO Meta settings
        $this->seo_meta['page_title'] = trans('seo.back.photos.create');
        
        // prepare data for the view
        $data = [
            'seo_meta' => $this->seo_meta,
        ];
        
        // return the view with data
        return view('pages.back.photos-edit')->with($data);
    }
    
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        // we check the current user permission
        if (!Permission::hasPermission('photos.create')) {
            // we redirect the current user to the photos list if he has the required permission
            if (Sentinel::getUser()->hasAccess('photos.page.view')) {
                return redirect()->route('photos.page.edit');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }
        
        // if the active field is not given, we set it to false
        $request->merge(['active' => $request->get('active', false)]);
        
        // we sanitize the entries
        $request->replace(Entry::sanitizeAll($request->all()));
        
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
            // we create the photo
            $photo = $this->repository->create($request->except('_token'));
            
            // we store the image
            if ($img = $request->file('cover')) {
                // we optimize, resize and save the image
                $file_name = ImageManager::optimizeAndResize(
                    $img->getRealPath(),
                    $photo->imageName('cover'),
                    $img->getClientOriginalExtension(),
                    $photo->storagePath(),
                    $photo->availableSizes('cover')
                );
                // we update the model with the image name
                $photo->cover = $file_name;
                $photo->save();
            }
            
            // we notify the current user
            Modal::alert([
                trans('photos.message.create.success', ['album' => $photo->title]),
            ], 'success');
            
            return redirect(route('photos.page.edit'));
        } catch (Exception $e) {
            // we flash the request
            $request->flashExcept('cover');

            // we log the error
            CustomLog::error($e);

            // we notify the current user
            Modal::alert([
                trans('photos.message.create.failure', ['album' => $request->title]),
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
        if (!Permission::hasPermission('photos.view')) {
            // we redirect the current user to the photos list if he has the required permission
            if (Sentinel::getUser()->hasAccess('photos.page.view')) {
                return redirect()->route('photos.page.edit');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }
        
        // we get the photo
        try {
            $photo = $this->repository->find($id);
        } catch (Exception $e) {
            // we notify the current user
            Modal::alert([
                trans('photos.message.find.failure', ['id' => $id]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');
            
            return redirect()->back();
        }
        
        // SEO Meta settings
        $this->seo_meta['page_title'] = trans('seo.back.photos.edit');
        
        // we prepare the data for breadcrumbs
        $breadcrumbs_data = [
            'photo' => $photo,
        ];

        // we format the date in correct format
        $photo->date = Carbon::createFromFormat('Y-m-d', $photo->date)->format('d/m/Y');
        
        // prepare data for the view
        $data = [
            'seo_meta'         => $this->seo_meta,
            'photo'            => $photo,
            'breadcrumbs_data' => $breadcrumbs_data,
        ];
        
        // return the view with data
        return view('pages.back.photos-edit')->with($data);
    }
    
    /**
     * @param $îd
     * @param Request $request
     * @return mixed
     */
    public function update($id, Request $request)
    {
        // we get the photo
        try {
            $photo = $this->repository->find($id);
        } catch (Exception $e) {
            // we flash the request
            $request->flashExcept('cover');
            
            // we notify the current user
            Modal::alert([
                trans('photos.message.find.failure', ['id' => $id]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');
            
            return redirect()->back();
        }
        
        // we check the current user permission
        if (!Permission::hasPermission('photos.update')) {
            // we redirect the current user to the permissions list if he has the required permission
            if (Sentinel::getUser()->hasAccess('photos.view')) {
                return redirect()->route('photos.edit', ['id' => $id]);
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }
        
        // if the active field is not given, we set it to false
        $request->merge(['active' => $request->get('active', false)]);
        
        // we sanitize the entries
        $request->replace(Entry::sanitizeAll($request->all()));
        
        // we convert the fr date to database format
        if ($request->get('date')) {
            $request->merge([
                'date' => Carbon::createFromFormat('m/d/Y', $request->get('date'))->format('Y-m-d'),
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
            // we update the photo
            $photo->update($request->except('_token', 'cover'));

            // we store the image
            if ($img = $request->file('cover')) {
                // we optimize, resize and save the image
                $file_name = ImageManager::optimizeAndResize(
                    $img->getRealPath(),
                    $photo->imageName('cover'),
                    $img->getClientOriginalExtension(),
                    $photo->storagePath(),
                    $photo->availableSizes('cover')
                );
                // we add the image name to the inputs for saving
                $photo->cover = $file_name;
                $photo->save();
            }
            
            // we notify the current user
            Modal::alert([
                trans('photos.message.update.success', ['album' => $photo->title]),
            ], 'success');
            
            return redirect()->back();
        } catch (Exception $e) {
            // we flash the request
            $request->flashExcept('cover');
            
            // we log the error
            CustomLog::error($e);
            
            // we notify the current user
            Modal::alert([
                trans('$photo.message.update.failure', ['album' => $photo->title]),
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
        // we get the photo
        try {
            $photo = $this->repository->find($id);
        } catch (Exception $e) {
            
            // we notify the current user
            Modal::alert([
                trans('photos.message.find.failure', ['id' => $id]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');
            
            return redirect()->back();
        }
        
        // we check the current user permission
        if (!Permission::hasPermission('photos.delete')) {
            // we redirect the current user to the photos list if he has the required permission
            if (Sentinel::getUser()->hasAccess('photos.page.view')) {
                return redirect()->route('photos.page.edit');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }
        
        try {
            // we remove the photo cover
            if ($photo->cover) {
                ImageManager::remove(
                    $photo->cover,
                    $photo->storagePath(),
                    $photo->availableSizes('cover')
                );
            }
            
            // we delete the role
            $photo->delete();
            
            Modal::alert([
                trans('photos.message.delete.success', ['album' => $photo->title]),
            ], 'success');
            
            return redirect()->back();
        } catch (Exception $e) {
            // we log the error
            CustomLog::error($e);
            
            // we notify the current user
            Modal::alert([
                trans('photos.message.delete.failure', ['album' => $photo->title]),
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
        // we get the photo
        try {
            $photo = $this->repository->find($id);
        } catch (Exception $e) {
            // we log the error
            CustomLog::error($e);

            // we notify the current user
            return response([
                'message' => [
                    trans('photos.message.find.failure', ['id' => $id]),
                    trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
                ],
            ], 401);
        }

        // we check the current user permission
        if ($permission_denied = Permission::hasPermissionJson('photos.update')) {
            return response([
                'active'  => $photo->active,
                'message' => [$permission_denied],
            ], 401);
        }
        
        // if the active field is not given, we set it to false
        $request->merge(['active' => $request->get('active', false)]);
        
        // we sanitize the entries
        $request->replace(Entry::sanitizeAll($request->all()));
        
        // we check inputs validity
        $rules = [
            'active' => 'required|boolean',
        ];
        if (is_array($errors = Validation::check($request->all(), $rules, true))) {
            return response([
                'active'  => $photo->active,
                'message' => $errors,
            ], 401);
        }
        
        try {
            $photo->active = $request->get('active');
            $photo->save();
            
            return response([
                'active'  => $photo->active,
                'message' => [
                    trans('photos.message.activation.success.label', ['action' => trans_choice('photos.message.activation.success.action', $photo->active), 'album' => $photo->title]),
                ],
            ], 200);
        } catch (Exception $e) {
            // we log the error
            CustomLog::error($e);
            
            // we notify the current user
            return response([
                'active'  => $photo->fresh()->active,
                'message' => trans('photos.message.activation.failure', ['album' => $photo->title]),
            ], 401);
        }
    }
    
}
