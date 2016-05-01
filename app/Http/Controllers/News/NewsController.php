<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Repositories\News\NewsRepositoryInterface;
use Carbon\Carbon;
use CustomLog;
use Entry;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use ImageManager;
use Modal;
use Parsedown;
use Permission;
use Sentinel;
use TableList;
use Validation;

class NewsController extends Controller
{
    
    /**
     * Create a new home controller instance.
     *
     * @return void
     */
    public function __construct(NewsRepositoryInterface $news)
    {
        parent::__construct();
        $this->repository = $news;
    }
    
    /**
     * @return $this
     */
    public function index()
    {
        // we get the json content
        $news_page = null;
        if (is_file(storage_path('app/news/content.json'))) {
            $news_page = json_decode(file_get_contents(storage_path('app/news/content.json')));
        }

        // we parse the markdown content
        $parsedown = new Parsedown();
        $description = isset($news_page->description) ? $parsedown->text($news_page->description) : null;

        // SEO Meta settings
        $this->seo_meta['page_title'] = trans('seo.front.news.title');
        $this->seo_meta['meta_desc'] = $description ? trans('seo.front.news.description') : str_limit($description, 160);
        $this->seo_meta['meta_keywords'] = trans('seo.front.news.keywords');

        // og meta settings
        $this->og_meta['og:title'] = trans('seo.front.news.title');
        $this->og_meta['og:description'] = $description ? trans('seo.front.news.description') : str_limit($description, 160);
        $this->og_meta['og:type'] = 'article';
        $this->og_meta['og:url'] = route('news.page.edit');
        
        // we get the category id
        $category = Input::get('category', null);
        
        // sort results by date
        $query = $this->repository
            ->getModel()
            ->where('active', true)
            ->where('released_at', '<=', Carbon::now()->format('Y-m-d H:i:s'))
            ->orderBy('released_at', 'desc');
        
        // if a category is given, we filter the list
        if ($category) {
            $query->where('category_id', $category);
        }
        
        // paginate the results
        $news_list = $query->paginate(10);
        
        // we convert in html the markdown content of each news
        if ($news_list) {
            $parsedown = new Parsedown();
            foreach ($news_list as $n) {
                $n->content = isset($n->content) ? $parsedown->text($n->content) : null;
            }
        }
        
        // prepare data for the view
        $data = [
            'seo_meta'         => $this->seo_meta,
            'og_meta'          => $this->og_meta,
            'news_list'        => $news_list,
            'current_category' => $category,
            'title'            => isset($news_page->title) ? $news_page->title : null,
            'background_image' => isset($news_page->background_image) ? $news_page->background_image : null,
            'description'      => $description,
            'css'              => elixir('css/app.news.css'),
        ];
        
        // return the view with data
        return view('pages.front.news-list')->with($data);
    }
    
    /**
     * @param $news_key
     * @return $this
     */
    public function show($id)
    {
        // we get the news from its unique key
        try {
            $news = $this->repository
                ->getModel()
                ->where('id', $id)
                ->where('active', true)
                ->where('released_at', '<=', Carbon::now()->format('Y-m-d H:i:s'))
                ->firstOrFail();
        } catch (Exception $e) {
            // we log the error
            CustomLog::error($e);
            
            Modal::alert([
                trans('news.message.find.failure', ['id' => $id]),
            ], 'error');
            
            // we trigger a 404
            abort(404);
        }
        
        // we parse the markdown content
        $parsedown = new Parsedown();
        $news->content = isset($news->content) ? $parsedown->text($news->content) : null;
        
        // SEO Meta settings
        $this->seo_meta['page_title'] = $news->meta_title ? $news->meta_title : $news->title;
        $this->seo_meta['meta_desc'] = $news->meta_desc ? $news->meta_desc : str_limit(strip_tags($news->content), 160);
        $this->seo_meta['meta_keywords'] = $news->meta_keywords;

        // og meta settings
        $this->og_meta['og:title'] = $news->meta_title ? $news->meta_title : $news->title;
        $this->og_meta['og:description'] = $news->meta_desc ? $news->meta_desc : str_limit(strip_tags($news->content), 160);
        $this->og_meta['og:type'] = 'article';
        $this->og_meta['og:url'] = route('news.show', ['id' => $news->id, 'key' => $news->key]);
        $this->og_meta['og:image'] = $news->imagePath($news->image, 'image', '767');
        
        // prepare data for the view
        $data = [
            'seo_meta' => $this->seo_meta,
            'og_meta'  => $this->og_meta,
            'news'     => $news,
            'css'      => elixir('css/app.news.css'),
            'js'       => elixir('js/app.news-detail.js'),
        ];
        
        // return the view with data
        return view('pages.front.news-detail')->with($data);
    }
    
    /**
     * @param Request $request
     * @return $this
     */
    public function pageEdit(Request $request)
    {
        // we check the current user permission
        if (!Permission::hasPermission('news.page.view')) {
            return redirect()->route('dashboard.index');
        }
        
        // SEO Meta settings
        $this->seo_meta['page_title'] = trans('seo.back.news.page.edit');
        
        // we define the table list columns
        $columns = [
            [
                'title' => trans('news.page.label.image'),
                'key'   => 'image',
                'image' => [
                    'storage_path' => $this->repository->getModel()->storagePath(),
                    'size'         => [
                        'thumbnail' => 'admin',
                        'detail'    => '767',
                    ],
                ],
            ],
            [
                'title'   => trans('news.page.label.title'),
                'key'     => 'title',
                'sort_by' => 'news.title',
            ],
            [
                'title'     => trans('news.page.label.content'),
                'key'       => 'content',
                'str_limit' => 75,
            ],
            [
                'title'   => trans('news.page.label.category'),
                'key'     => 'category_id',
                'config'  => 'news.category',
                'trans'   => 'news.config.category',
                'sort_by' => 'news.category_id',
                'button'  => true,
            ],
            [
                'title'           => trans('news.page.label.released_at'),
                'key'             => 'released_at',
                'sort_by'         => 'news.released_at',
                'sort_by_default' => 'desc',
                'date'            => 'd/m/Y H:i',
            ],
            [
                'title'    => trans('news.page.label.activation'),
                'key'      => 'active',
                'activate' => [
                    'route'  => 'news.activate',
                    'params' => [],
                ],
            ],
        ];
        
        // we set the routes used in the table list
        $routes = [
            'index'   => [
                'route'  => 'news.page.edit',
                'params' => [],
            ],
            'create'  => [
                'route'  => 'news.create',
                'params' => [],
            ],
            'edit'    => [
                'route'  => 'news.edit',
                'params' => [],
            ],
            'destroy' => [
                'route'  => 'news.destroy',
                'params' => [],
            ],
        ];
        
        // we instantiate the query
        $query = $this->repository->getModel()->query();
        
        // we prepare the confirm config
        $confirm_config = [
            'action'     => trans('news.page.action.delete'),
            'attributes' => ['title'],
        ];
        
        // we prepare the search config
        $search_config = [
            [
                'key'      => trans('news.page.label.title'),
                'database' => 'news.title',
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
        $news_page = null;
        if (is_file(storage_path('app/news/content.json'))) {
            $news_page = json_decode(file_get_contents(storage_path('app/news/content.json')));
        }
        
        // prepare data for the view
        $data = [
            'title'            => isset($news_page->title) ? $news_page->title : null,
            'description'      => isset($news_page->description) ? $news_page->description : null,
            'background_image' => isset($news_page->background_image) ? $news_page->background_image : null,
            'tableListData'    => $tableListData,
            'seo_meta'         => $this->seo_meta,
        ];
        
        // return the view with data
        return view('pages.back.news-page-edit')->with($data);
    }
    
    /**
     * @param Request $request
     * @return mixed
     */
    public function pageUpdate(Request $request)
    {
        // we check the current user permission
        if (!Permission::hasPermission('news.page.update')) {
            // we redirect the current user to the news page view if he has the required permission
            if (Sentinel::getUser()->hasAccess('news.page.view')) {
                return redirect()->route('news.page.edit');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }
        
        // we get the json news content
        $news = null;
        if (is_file(storage_path('app/news/content.json'))) {
            $news = json_decode(file_get_contents(storage_path('app/news/content.json')));
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
                    config('image.news.background_image.name'),
                    $background_image->getClientOriginalExtension(),
                    config('image.news.storage_path'),
                    config('image.news.background_image.sizes')
                );
                // we set the file name
                $inputs['background_image'] = $file_name;
            } elseif ($request->get('remove_background_image')) {
                // we remove the background image
                if (isset($news->background_image)) {
                    ImageManager::remove(
                        $news->background_image,
                        config('image.news.storage_path'),
                        config('image.news.background_image.sizes')
                    );
                }
                $inputs['background_image'] = null;
            } else {
                $inputs['background_image'] = isset($news->background_image) ? $news->background_image : null;
            }
            
            // we store the content into a json file
            file_put_contents(
                storage_path('app/news/content.json'),
                json_encode($inputs)
            );
            
            Modal::alert([
                trans('news.message.content.update.success', ['title' => $request->get('title')]),
            ], 'success');
            
            return redirect()->back();
        } catch (Exception $e) {
            
            // we flash the request
            $request->flashExcept('background_image');
            
            // we log the error
            CustomLog::error($e);
            
            // we notify the current user
            Modal::alert([
                trans('news.message.content.update.failure', ['title' => isset($news->title) ? $news->title : null]),
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
        if (!Permission::hasPermission('news.create')) {
            // we redirect the current user to the news page edit if he has the required permission
            if (Sentinel::getUser()->hasAccess('news.page.view')) {
                return redirect()->route('news.page.edit');
            } else {
                // or we redirect the current user to the dashboard
                return redirect()->route('dashboard.index');
            }
        }
        
        // SEO Meta settings
        $this->seo_meta['page_title'] = trans('seo.back.news.create');
        
        // prepare data for the view
        $data = [
            'seo_meta'   => $this->seo_meta,
            'categories' => config('news.category'),
        ];
        
        // return the view with data
        return view('pages.back.news-edit')->with($data);
    }
    
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        // we check the current user permission
        if (!Permission::hasPermission('news.create')) {
            // we redirect the current user to the news list if he has the required permission
            if (Sentinel::getUser()->hasAccess('news.page.view')) {
                return redirect()->route('news.page.edit');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }
        
        // if the active field is not given, we set it to false
        $request->merge(['active' => $request->get('active', false)]);
        
        // we sanitize the entries
        $request->replace(Entry::sanitizeAll($request->all()));
        
        // we check if the current user has the permission to activate the news
        if ($request->get('active') && !Permission::hasPermission('news.activate')) {
            return redirect()->back();
        }
        
        // we convert the title into a slug
        $request->merge([
            'key' => str_slug($request->get('title')),
        ]);
        
        // we convert the fr date to database format
        if ($request->get('released_at')) {
            $request->merge([
                'released_at' => Carbon::createFromFormat('d/m/Y H:i', $request->get('released_at'))
                    ->format('Y-m-d H:i:s'),
            ]);
        }
        
        // we check inputs validity
        $rules = [
            'category_id'      => 'required|in:' . implode(',', array_keys(config('news.category'))),
            'image'            => 'image|mimes:jpg,jpeg,png|image_size:>=2560,>=1440',
            'key'              => 'alpha_dash|unique:news,key',
            'title'            => 'required|string',
            'meta_title'       => 'string',
            'meta_description' => 'string',
            'meta_keywords'    => 'string',
            'content'          => 'string',
            'released_at'      => 'required|date_format:Y-m-d H:i:s',
            'active'           => 'required|boolean',
        ];
        // we check the inputs validity
        if (!Validation::check($request->all(), $rules)) {
            // we flash the request
            $request->flashExcept('image');
            
            return redirect()->back();
        }
        
        try {
            // we create the news
            $news = $this->repository->create($request->except('_token'));
            
            // we store the image
            if ($img = $request->file('image')) {
                // we optimize, resize and save the image
                $file_name = ImageManager::optimizeAndResize(
                    $img->getRealPath(),
                    $news->imageName('image'),
                    $img->getClientOriginalExtension(),
                    $news->storagePath(),
                    $news->availableSizes('image')
                );
                // we update the model with the image name
                $news->image = $file_name;
                $news->save();
            }
            
            // we notify the current user
            Modal::alert([
                trans('news.message.create.success', ['news' => $news->title]),
            ], 'success');
            
            return redirect(route('news.page.edit'));
        } catch (Exception $e) {
            // we flash the request
            $request->flashExcept('image');
            
            // we log the error
            CustomLog::error($e);
            
            // we notify the current user
            Modal::alert([
                trans('news.message.update.failure', ['news' => $request->get('title')]),
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
        if (!Permission::hasPermission('news.view')) {
            // we redirect the current user to the news list if he has the required permission
            if (Sentinel::getUser()->hasAccess('news.page.view')) {
                return redirect()->route('news.page.edit');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }
        
        // we get the news
        try {
            $news = $this->repository->find($id);
        } catch (Exception $e) {
            // we notify the current user
            Modal::alert([
                trans('news.message.find.failure', ['id' => $id]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');
            
            return redirect()->back();
        }
        
        // SEO Meta settings
        $this->seo_meta['page_title'] = trans('seo.back.news.edit');
        
        // we convert the database date to the fr/en format
        if ($released_at = $news->released_at) {
            $news->released_at = Carbon::createFromFormat('Y-m-d H:i:s', $released_at)->format('d/m/Y H:i');
        }
        
        // we prepare the data for breadcrumbs
        $breadcrumbs_data = [
            'news' => $news,
        ];
        
        // prepare data for the view
        $data = [
            'seo_meta'         => $this->seo_meta,
            'news'             => $news,
            'categories'       => config('news.category'),
            'breadcrumbs_data' => $breadcrumbs_data,
        ];
        
        // return the view with data
        return view('pages.back.news-edit')->with($data);
    }
    
    /**
     * @param $îd
     * @param Request $request
     * @return mixed
     */
    public function update($id, Request $request)
    {
        // we get the news
        try {
            $news = $this->repository->find($id);
        } catch (Exception $e) {
            // we flash the request
            $request->flashExcept('image');
            
            // we notify the current user
            Modal::alert([
                trans('news.message.find.failure', ['id' => $id]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');
            
            return redirect()->back();
        }
        
        // we check the current user permission
        if (!Permission::hasPermission('news.update')) {
            // we redirect the current user to the permissions list if he has the required permission
            if (Sentinel::getUser()->hasAccess('news.view')) {
                return redirect()->route('news.edit', ['id' => $id]);
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }
        
        // if the active field is not given, we set it to false
        $request->merge(['active' => $request->get('active', false)]);
        
        // we sanitize the entries
        $request->replace(Entry::sanitizeAll($request->all()));
        
        // we check if the current user has the permission to activate the news
        if ($request->get('active') && !Permission::hasPermission('news.activate')) {
            // we flash the request
            $request->flashExcept('image');
            
            return redirect()->back();
        }
        
        // we convert the title into a slug
        $request->merge([
            'key' => str_slug($request->get('title')),
        ]);
        
        // we convert the fr date to database format
        if ($request->get('released_at')) {
            $request->merge([
                'released_at' => Carbon::createFromFormat('d/m/Y H:i', $request->get('released_at'))
                    ->format('Y-m-d H:i:s'),
            ]);
        }
        
        // we check inputs validity
        $rules = [
            'category_id'      => 'required|in:' . implode(',', array_keys(config('news.category'))),
            'image'            => 'image|mimes:jpg,jpeg,png|image_size:>=2560,>=1440',
            'key'              => 'required|alpha_dash|unique:news,key,' . $request->get('_id'),
            'title'            => 'required|string',
            'meta_title'       => 'string',
            'meta_description' => 'string',
            'meta_keywords'    => 'string',
            'content'          => 'string',
            'released_at'      => 'required|date_format:Y-m-d H:i:s',
            'active'           => 'required|boolean',
        ];
        // we check the inputs validity
        if (!Validation::check($request->all(), $rules)) {
            // we flash the request
            $request->flashExcept('image');
            
            return redirect()->back();
        }
        
        try {
            // we update the news
            $news->update($request->except('_token', 'image'));
            
            // we store the image
            if ($img = $request->file('image')) {
                // we optimize, resize and save the image
                $file_name = ImageManager::optimizeAndResize(
                    $img->getRealPath(),
                    $news->imageName('image'),
                    $img->getClientOriginalExtension(),
                    $news->storagePath(),
                    $news->availableSizes('image')
                );
                // we add the image name to the inputs for saving
                $news->image = $file_name;
                $news->save();
            }
            
            // we notify the current user
            Modal::alert([
                trans('news.message.update.success', ['news' => $news->title]),
            ], 'success');
            
            return redirect()->back();
        } catch (Exception $e) {
            // we flash the request
            $request->flashExcept('image');
            
            // we log the error
            CustomLog::error($e);
            
            // we notify the current user
            Modal::alert([
                trans('news.message.update.failure', ['news' => $news->title]),
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
        // we get the news
        try {
            $news = $this->repository->find($id);
        } catch (Exception $e) {
            
            // we notify the current user
            Modal::alert([
                trans('news.message.find.failure', ['id' => $id]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');
            
            return redirect()->back();
        }
        
        // we check the current user permission
        if (!Permission::hasPermission('news.delete')) {
            // we redirect the current user to the permissions list if he has the required permission
            if (Sentinel::getUser()->hasAccess('news.page.view')) {
                return redirect()->route('news.page.edit');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }
        
        try {
            // we remove the news image
            if ($news->image) {
                ImageManager::remove(
                    $news->image,
                    $news->storagePath(),
                    $news->availableSizes('image')
                );
            }
            
            // we delete the role
            $news->delete();
            
            Modal::alert([
                trans('news.message.delete.success', ['news' => $news->title]),
            ], 'success');
            
            return redirect()->back();
        } catch (Exception $e) {
            // we log the error
            CustomLog::error($e);
            
            // we notify the current user
            Modal::alert([
                trans('news.message.delete.failure', ['news' => $news->title]),
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
        // we get the news
        try {
            $news = $this->repository->find($id);
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
        if ($permission_denied = Permission::hasPermissionJson('news.update')) {
            return response([
                'active'  => $news->active,
                'message' => [$permission_denied],
            ], 401);
        }

        if ($permission_denied = Permission::hasPermissionJson('news.activate')) {
            return response([
                'active'  => $news->active,
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
                'active'  => $news->active,
                'message' => $errors,
            ], 401);
        }
        
        try {
            $news->active = $request->get('active');
            $news->save();
            
            return response([
                'active'  => $news->active,
                'message' => [
                    trans('news.message.activation.success.label', ['action' => trans_choice('news.message.activation.success.action', $news->active), 'news' => $news->title]),
                ],
            ], 200);
        } catch (Exception $e) {
            // we log the error
            CustomLog::error($e);
            
            // we notify the current user
            return response([
                'active'  => $news->fresh()->active,
                'message' => trans('news.message.activation.failure', ['news' => $news->title]),
            ], 401);
        }
    }
    
}
