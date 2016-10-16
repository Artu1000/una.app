<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Repositories\Page\PageRepositoryInterface;
use CustomLog;
use Exception;
use FileManager;
use Illuminate\Http\Request;
use ImageManager;
use InputSanitizer;
use Modal;
use Parsedown;
use Permission;
use Sentinel;
use TableList;
use Validation;


class PageController extends Controller
{
    /**
     * Create a new home controller instance.
     *
     * @return void
     */
    public function __construct(PageRepositoryInterface $page)
    {
        parent::__construct();
        $this->repository = $page;
    }
    
    public function index(Request $request)
    {
        // we check the current user permission
        if (!Permission::hasPermission('pages.list')) {
            return redirect()->route('backoffice.index');
        }
        
        // SEO Meta settings
        $this->seo_meta['page_title'] = trans('seo.back.pages.index');
        
        // we define the table list columns
        $columns = [
            [
                'title' => trans('pages.page.label.image'),
                'key'   => 'image',
                'image' => [
                    'storage_path' => $this->repository->getModel()->storagePath(),
                    'size'         => [
                        'thumbnail' => 'admin',
                        'detail'    => '767',
                    ],
                ],
            ], [
                'title'   => trans('pages.page.label.slug'),
                'key'     => 'slug',
                'sort_by' => 'pages.slug',
            ], [
                'title'   => trans('pages.page.label.title'),
                'key'     => 'title',
                'sort_by' => 'pages.title',
            ], [
                'title'     => trans('pages.page.label.content'),
                'key'       => 'content',
                'str_limit' => 75,
            ], [
                'title'   => trans('pages.page.label.created_at'),
                'key'     => 'created_at',
                'date'    => 'd/m/Y H:i:s',
                'sort_by' => 'pages.created_at',
            ], [
                'title'   => trans('pages.page.label.updated_at'),
                'key'     => 'updated_at',
                'date'    => 'd/m/Y H:i:s',
                'sort_by' => 'pages.updated_at',
            ], [
                'title'    => trans('pages.page.label.activation'),
                'key'      => 'active',
                'activate' => [
                    'route'  => 'pages.activate',
                    'params' => [],
                ],
            ],
        ];
        
        // we set the routes used in the table list
        $routes = [
            'index'   => [
                'route'  => 'pages.index',
                'params' => [],
            ],
            'create'  => [
                'route'  => 'pages.create',
                'params' => [],
            ],
            'edit'    => [
                'route'  => 'pages.edit',
                'params' => [],
            ],
            'destroy' => [
                'route'  => 'pages.destroy',
                'params' => [],
            ],
        ];
        
        // we instantiate the query
        $query = $this->repository->getModel()->query();
        
        // we group the results
        $query->groupBy('pages.id');
        
        // we select the data we want
        $query->select('pages.*');
        
        $confirm_config = [
            'action'     => trans('pages.page.action.delete'),
            'attributes' => ['title'],
        ];
        
        // we prepare the search config
        $search_config = [
            [
                'key'      => trans('pages.page.label.title'),
                'database' => 'pages.title',
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
        
        // prepare data for the view
        $data = [
            'tableListData' => $tableListData,
            'seo_meta'      => $this->seo_meta,
        ];
        
        // return the view with data
        return view('pages.back.pages-list')->with($data);
    }
    
    
    /**
     * @param $slug
     * @return $this
     */
    public function show($slug)
    {
        // we get the page from its slug
        try {
            $page = $this->repository->getModel()
                ->where('slug', $slug)
                ->where('active', true)
                ->firstOrFail();
            
            // we check if the ths slug is correctly translated
            if ($page->slug !== $slug) {
                return redirect()->route('page.show', ['slug' => $page->slug]);
            }
            
        } catch (Exception $e) {
            // we log the error
            CustomLog::error($e);
            
            return abort(404);
        }
    
        // we parse the markdown content
        $parsedown = new Parsedown();
        $page->content = isset($page->content) ? $parsedown->text($page->content) : null;
        // we replace the images aliases by real paths
        $page->content = ImageManager::replaceLibraryImagesAliasesByRealPath($page->content);
        // we replace the files aliases by real paths
        $page->content = FileManager::replaceLibraryFilesAliasesByRealPath($page->content);
        
        // SEO Meta settings
        $this->seo_meta['page_title'] = $page->meta_title ? $page->meta_title : strip_tags($page->title);
        $this->seo_meta['meta_desc'] = $page->meta_description ? $page->meta_description : str_limit(strip_tags($page->content), 160);
        $this->seo_meta['meta_keywords'] = $page->meta_keywords;
        
        // og meta settings
        $this->og_meta['og:title'] = $page->meta_title ? $page->meta_title : strip_tags($page->title);
        $this->og_meta['og:description'] = $page->meta_description ? $page->meta_description : str_limit(strip_tags($page->content), 160);
        $this->og_meta['og:type'] = 'article';
        $this->og_meta['og:url'] = route('page.show', ['slug ' => $page->slug]);
        if ($page->image) {
            $this->og_meta['og:image'] = $page->imagePath($page->image, 'image', '767');
        }
        
        // prepare data for the view
        $data = [
            'seo_meta' => $this->seo_meta,
            'og_meta'  => $this->og_meta,
            'page'     => $page,
            'css'      => elixir('css/app.page.css'),
        ];
        
        // return the view with data
        return view('pages.front.page')->with($data);
    }
    
    /**
     * @return $this
     */
    public function create()
    {
        // we check the current user permission
        if (!Permission::hasPermission('pages.create')) {
            // we redirect the current user to the page edit page if he has the required permission
            if (Sentinel::getUser()->hasAccess('pages.view')) {
                return redirect()->route('pages.index');
            } else {
                // or we redirect the current user to the dashboard
                return redirect()->route('backoffice.index');
            }
        }
        
        // SEO Meta settings
        $this->seo_meta['page_title'] = trans('seo.back.pages.create');
        
        // prepare data for the view
        $data = [
            'seo_meta' => $this->seo_meta,
        ];
        
        // return the view with data
        return view('pages.back.page-edit')->with($data);
    }
    
    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        // we check the current user permission
        if (!Permission::hasPermission('pages.create')) {
            // we redirect the current user to the pages list if he has the required permission
            if (Sentinel::getUser()->hasAccess('pages.view')) {
                return redirect()->route('pages.index');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('backoffice.index');
            }
        }
        
        // if the active field is not given, we set it to false
        $request->merge(['active' => $request->get('active', false)]);
        
        // we sanitize the entries
        $request->replace(InputSanitizer::sanitize($request->all()));
    
        // we slugify the slug
        $request->merge([
            'slug' => str_slug(strip_tags($request->slug)),
        ]);
    
        // we set the validity rules
        $rules = [
            'image'            => 'image|mimes:jpg,jpeg,png|image_size:>=2560,>=1440',
            'active'           => 'required|boolean',
            'slug'             => 'required_with:title|alpha_dash|unique:pages,slug',
            'title'            => 'required|string',
            'content'          => 'string',
            'meta_title'       => 'string',
            'meta_description' => 'string',
            'meta_keywords'    => 'string',
        ];
        // we check the inputs validity
        if (!Validation::check($request->all(), $rules)) {
            // we flash the request
            $request->flashExcept('image');
            
            return redirect()->back();
        }
        
        try {
            // we create the page
            $page = $this->repository->create([
                'active' => $request->active,
            ]);
            
            // we update the fields
            if(Sentinel::getUser()->hasAccess('pages.slug')){
                $page->slug = $request->slug;
            }
            $page->title = $request->title;
            $page->content = $request->content;
            $page->meta_title = $request->meta_title;
            $page->meta_description = $request->meta_description;
            $page->meta_keywords = $request->meta_keywords;
            
            // we store the image
            if ($img = $request->file('image')) {
                // we optimize, resize and save the image
                $file_name = ImageManager::optimizeAndResize(
                    $img->getRealPath(),
                    $page->imageName('image'),
                    $img->getClientOriginalExtension(),
                    $page->storagePath(),
                    $page->availableSizes('image')
                );
                // we update the model with the image name
                $page->image = $file_name;
            }
            
            // we save the page
            $page->save();
            
            // we notify the current user
            Modal::alert([
                trans('pages.message.create.success', ['page' => $page->title]),
            ], 'success');
            
            return redirect(route('pages.index'));
        } catch (Exception $e) {
            // we flash the request
            $request->flashExcept('image');
            
            // we log the error
            CustomLog::error($e);
            
            // we notify the current user
            Modal::alert([
                trans('pages.message.create.failure', ['page' => $request->title]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email'),]),
            ], 'error');
            
            return redirect()->back();
        }
    }
    
    /**
     * @param $id
     * @return mixed
     */
    public function edit($id)
    {
        // we check the current user permission
        if (!Permission::hasPermission('pages.view')) {
            // we redirect the current user to the news list if he has the required permission
            if (Sentinel::getUser()->hasAccess('pages.list')) {
                return redirect()->route('pages.index');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('backoffice.index');
            }
        }
        
        // we get the news
        try {
            $page = $this->repository->find($id);
        } catch (Exception $e) {
            // we notify the current user
            Modal::alert([
                trans('pages.message.find.failure', ['id' => $id]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');
            
            return redirect()->back();
        }
        
        // SEO Meta settings
        $this->seo_meta['page_title'] = trans('seo.back.pages.edit', ['page' => strip_tags($page->title)]);
        
        // we prepare the data for breadcrumbs
        $breadcrumbs_data = [
            'page' => $page,
        ];
        
        // prepare data for the view
        $data = [
            'seo_meta'         => $this->seo_meta,
            'page'             => $page,
            'breadcrumbs_data' => $breadcrumbs_data,
        ];
        
        // return the view with data
        return view('pages.back.page-edit')->with($data);
    }
    
    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function update($id, Request $request)
    {
        // we get the page
        try {
            $page = $this->repository->find($id);
        } catch (Exception $e) {
            // we flash the request
            $request->flashExcept('image');
            
            // we notify the current user
            Modal::alert([
                trans('pages.message.find.failure', ['id' => $id]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');
            
            return redirect()->back();
        }
        
        // we check the current user permission
        if (!Permission::hasPermission('pages.update')) {
            // we redirect the current user to the permissions list if he has the required permission
            if (Sentinel::getUser()->hasAccess('pages.view')) {
                return redirect()->route('pages.edit', ['id' => $id]);
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('backoffice.index');
            }
        }
        
        // if the active field is not given, we set it to false
        $request->merge(['active' => $request->get('active', false)]);
        
        // we sanitize the entries
        $request->replace(InputSanitizer::sanitize($request->all()));
        
        // we slugify the slug
        $request->merge([
            'slug' => str_slug(strip_tags($request->slug)),
        ]);
        
        // we set the validity rules
        $rules = [
            'image'            => 'image|mimes:jpg,jpeg,png|image_size:>=2560,>=1440',
            'active'           => 'required|boolean',
            'slug'             => 'required_with:title|alpha_dash|unique:pages,slug,' . $page->id,
            'title'            => 'required|string',
            'content'          => 'string',
            'meta_title'       => 'string',
            'meta_description' => 'string',
            'meta_keywords'    => 'string',
        ];
        
        // we check inputs validity
        if (!Validation::check($request->all(), $rules)) {
            // we flash the request
            $request->flashExcept('image');
            
            return redirect()->back();
        }
        
        try {
            // we update the fields
            if(Sentinel::getUser()->hasAccess('pages.slug')){
                $page->slug = $request->slug;
            }
            $page->title = $request->title;
            $page->content = $request->content;
            $page->meta_title = $request->meta_title;
            $page->meta_description = $request->meta_description;
            $page->meta_keywords = $request->meta_keywords;
            $page->active = $request->active;
            
            // we store the image
            if ($img = $request->file('image')) {
                // if we find a previous recorded image, we remove it
                if ($page->image) {
                    ImageManager::remove(
                        $page->image,
                        $page->storagePath(),
                        $page->availableSizes('image')
                    );
                }
                // we optimize, resize and save the image
                $file_name = ImageManager::optimizeAndResize(
                    $img->getRealPath(),
                    $page->imageName('image'),
                    $img->getClientOriginalExtension(),
                    $page->storagePath(),
                    $page->availableSizes('image')
                );
                // we add the image name to the inputs for saving
                $page->image = $file_name;
            }
            
            // we save the changes
            $page->save();
            
            // we notify the current user
            Modal::alert([
                trans('pages.message.update.success', ['page' => $page->title]),
            ], 'success');
            
            return redirect()->back();
        } catch (Exception $e) {
            // we flash the request
            $request->flashExcept('image');
            
            // we log the error
            CustomLog::error($e);
            
            // we notify the current user
            Modal::alert([
                trans('pages.message.update.failure', ['page' => $page->title]),
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
        // we get the page
        try {
            $page = $this->repository->find($id);
        } catch (Exception $e) {
            
            // we notify the current user
            Modal::alert([
                trans('pages.message.find.failure', ['id' => $id]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');
            
            return redirect()->back();
        }
        
        // we check the current user permission
        if (!Permission::hasPermission('pages.delete')) {
            // we redirect the current user to the permissions list if he has the required permission
            if (Sentinel::getUser()->hasAccess('pages.page.view')) {
                return redirect()->route('pages.page.edit');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('backoffice.index');
            }
        }
        
        try {
            // we remove the pages image
            if ($page->image) {
                ImageManager::remove(
                    $page->image,
                    $page->storagePath(),
                    $page->availableSizes('image')
                );
            }
            
            Modal::alert([
                trans('pages.message.delete.success', ['page' => $page->title]),
            ], 'success');
            
            // we delete the role
            $page->delete();
            
            return redirect()->back();
        } catch (Exception $e) {
            // we log the error
            CustomLog::error($e);
            
            // we notify the current user
            Modal::alert([
                trans('pages.message.delete.failure', ['page' => $page->title]),
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
        // we get the page
        try {
            $page = $this->repository->find($id);
        } catch (Exception $e) {
            // we log the error
            CustomLog::error($e);
            
            // we notify the current user
            return response([
                'message' => [
                    trans('news.message.find.failure', ['id' => $id]),
                    trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
                ],
            ], 401);
        }
        
        // we check the current user permission
        if ($permission_denied = Permission::hasPermissionJson('pages.update')) {
            return response([
                'active'  => $page->active,
                'message' => [$permission_denied],
            ], 401);
        }
        
        if ($permission_denied = Permission::hasPermissionJson('pages.activate')) {
            return response([
                'active'  => $page->active,
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
                'active'  => $page->active,
                'message' => $errors,
            ], 401);
        }
        
        try {
            $page->active = $request->active;
            $page->save();
            
            return response([
                'active'  => $page->active,
                'message' => [
                    trans('pages.message.activation.success.label', ['action' => trans_choice('pages.message.activation.success.action', $page->active), 'page' => $page->title]),
                ],
            ], 200);
        } catch (Exception $e) {
            // we log the error
            CustomLog::error($e);
            
            // we notify the current user
            return response([
                'active'  => $page->fresh()->active,
                'message' => trans('pages.message.activation.failure', ['page' => $page->title]),
            ], 401);
        }
    }
}
