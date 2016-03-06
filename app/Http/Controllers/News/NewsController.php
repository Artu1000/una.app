<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Repositories\News\NewsRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

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
        // SEO Meta settings
        $this->seoMeta['page_title'] = 'Actualités';
        $this->seoMeta['meta_desc'] = 'Portes-ouvertes, stages, résultats de compétitions, événements sportifs...
        Consultez les actualités du club Université Nantes Aviron !';
        $this->seoMeta['meta_keywords'] = 'actus, actualités, club, universite, nantes, aviron, sport, universitaire,
        etudiant, ramer';

        // we get the category id
        $category = Input::get('category', null);

        // sort results by date
        $query = $this->repository->where('active', true)->orderBy('released_at', 'desc');

        // if a category is given, we filter the list
        if ($category) {
            $query->where('category_id', $category);
        }

        // paginate the results
        $news_list = $query->paginate(10);

        // we convert in html the markdown content of each news
        if ($news_list) {
            $parsedown = new \Parsedown();
            foreach ($news_list as $n) {
                $n->content = isset($n->content) ? $parsedown->text($n->content) : null;
            }
        }

        // prepare data for the view
        $data = [
            'seoMeta'          => $this->seoMeta,
            'news_list'        => $news_list,
            'current_category' => $category,
            'css'              => url(elixir('css/app.news.css')),
        ];

        // return the view with data
        return view('pages.front.news-list')->with($data);
    }

    /**
     * @param $news_key
     * @return $this
     */
    public function show($news_key)
    {
        // we get the news from its unique key
        $news = $this->repository->findBy('key', $news_key);

        // we parse the markdown content
        $parsedown = new \Parsedown();
        $news->content = isset($news->content) ? $parsedown->text($news->content) : null;

        if (!$news) {
            abort(404);
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = $news->meta_title ? $news->meta_title : $news->title;
        $this->seoMeta['meta_desc'] = $news->meta_desc ? $news->meta_desc : str_limit(strip_tags($news->content), 160);
        $this->seoMeta['meta_keywords'] = $news->meta_keywords;

        // prepare data for the view
        $data = [
            'seoMeta' => $this->seoMeta,
            'news'    => $news,
            'css'     => url(elixir('css/app.news.css')),
            'js'      => url(elixir('js/app.news-detail.js')),
        ];

        // return the view with data
        return view('pages.front.news-detail')->with($data);
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function adminList(Request $request)
    {
        // we check the current user permission
        if (!$this->requirePermission('news.list')) {
            return redirect()->back();
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.back.news.list');

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
                'str_limit' => 150,
            ],
            [
                'title'   => trans('news.page.label.category'),
                'key'     => 'category_id',
                'config'  => 'news.category',
                'sort_by' => 'news.category_id',
                'button'  => true,
            ],
            [
                'title'           => trans('news.page.label.released_at'),
                'key'             => 'released_at',
                'sort_by'         => 'news.released_at',
                'sort_by_default' => true,
                'date'            => 'd/m/Y H:i',
            ],
            [
                'title'    => trans('news.page.label.activation'),
                'key'      => 'active',
                'activate' => 'news.activate',
            ],
        ];

        // we set the routes used in the table list
        $routes = [
            'index'   => 'news.list',
            'create'  => 'news.create',
            'edit'    => 'news.edit',
            'destroy' => 'news.destroy',
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
            'title',
        ];

        // we enable the lines choice
        $enable_lines_choice = true;

        // we format the data for the needs of the view
        $tableListData = $this->prepareTableListData(
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
            'seoMeta'       => $this->seoMeta,
        ];

        // return the view with data
        return view('pages.back.news-list')->with($data);
    }

    /**
     * @return $this
     */
    public function create()
    {
        // we check the current user permission
        if (!$this->requirePermission('news.create')) {
            return redirect()->back();
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.back.news.create');

        // prepare data for the view
        $data = [
            'seoMeta'    => $this->seoMeta,
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
        if (!$this->requirePermission('news.create')) {
            return redirect()->back();
        }

        // we convert the "on" value to a boolean value
        $request->merge([
            'active' => filter_var($request->get('active'), FILTER_VALIDATE_BOOLEAN),
        ]);

        // we convert the title into a slug
        $request->merge([
            'key' => str_slug($request->get('title')),
        ]);

        // we convert the fr date to database format
        if ($request->get('released_at')) {
            $request->merge([
                'released_at' => \Carbon\Carbon::createFromFormat('d/m/Y H:i', $request->get('released_at'))
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
        if (!$this->checkInputsValidity($request->all(), $rules, $request)) {
            return redirect()->back();
        };

        try {
            // we create the news
            $news = $this->repository->create($request->except('_token'));

            // we store the image
            if ($img = $request->file('image')) {
                // we optimize, resize and save the image
                $file_name = \ImageManager::optimizeAndResize(
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
            \Modal::alert([
                trans('news.message.create.success'),
            ], 'success');

            return redirect(route('news.list'));
        } catch (\Exception $e) {
            // we flash the request
            $request->flash();

            // we log the error and we notify the current user
            \Log::error($e);
            \Modal::alert([
                trans('news.message.update.failure'),
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
        if (!$this->requirePermission('news.view')) {
            return redirect()->back();
        }

        // we get the news
        $news = $this->repository->find($id);

        // we check if the news exists
        if (!$news) {
            \Modal::alert([
                trans('news.message.find.failure', ['id' => $id]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email'),]),
            ], 'error');

            return redirect()->back();
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.back.news.edit');

        // we convert the database date to the fr/en format
        if ($released_at = $news->released_at) {
            $news->released_at = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $released_at)->format('d/m/Y H:i');
        }

        // we prepare the data for breadcrumbs
        $breadcrumbs_data = [
            $news->title,
        ];

        // prepare data for the view
        $data = [
            'seoMeta'          => $this->seoMeta,
            'news'             => $news,
            'categories'       => config('news.category'),
            'breadcrumbs_data' => $breadcrumbs_data,
        ];

        // return the view with data
        return view('pages.back.news-edit')->with($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // we check the current user permission
        if (!$this->requirePermission('news.update')) {
            return redirect()->back();
        }

        // we get the user
        if (!$news = $this->repository->find($request->get('_id'))) {
            \Modal::alert([
                trans('news.message.find.failure', ['id' => $request->get('_id')]),
            ], 'error');

            return redirect()->back();
        }

        // we convert the "on" value to a boolean value
        $request->merge([
            'active' => filter_var($request->get('active'), FILTER_VALIDATE_BOOLEAN),
        ]);

        // we convert the title into a slug
        $request->merge([
            'key' => str_slug($request->get('title')),
        ]);

        // we convert the fr date to database format
        if ($request->get('released_at')) {
            $request->merge([
                'released_at' => \Carbon\Carbon::createFromFormat('d/m/Y H:i', $request->get('released_at'))
                    ->format('Y-m-d H:i:s'),
            ]);
        }

        $inputs = $request->all();

        // we check inputs validity
        $rules = [
            'category_id'      => 'required|in:' . implode(',', array_keys(config('news.category'))),
            'image'            => 'image|mimes:png|image_size:>=2560,>=1440',
            'key'              => 'required|alpha_dash|unique:news,key,' . $request->get('_id'),
            'title'            => 'required|string',
            'meta_title'       => 'string',
            'meta_description' => 'string',
            'meta_keywords'    => 'string',
            'content'          => 'string',
            'released_at'      => 'required|date_format:Y-m-d H:i:s',
            'active'           => 'required|boolean',
        ];
        if (!$this->checkInputsValidity($inputs, $rules, $request)) {
            return redirect()->back();
        }

        try {
            // we store the image
            if ($img = $request->file('image')) {
                // we optimize, resize and save the image
                $file_name = \ImageManager::optimizeAndResize(
                    $img->getRealPath(),
                    $news->imageName('image'),
                    $img->getClientOriginalExtension(),
                    $news->storagePath(),
                    $news->availableSizes('image')
                );
                // we add the image name to the inputs for saving
                $inputs['image'] = $file_name;
            }

            // we update the news
            $news->update($inputs);

            // we notify the current user
            \Modal::alert([
                trans('news.message.update.success'),
            ], 'success');

            return redirect()->back();
        } catch (\Exception $e) {
            // we flash the request
            $request->flash();

            // we log the error and we notify the current user
            \Log::error($e);
            \Modal::alert([
                trans('news.message.update.failure'),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email'),]),
            ], 'error');

            return redirect()->back();
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        // we check the current user permission
        if (!$this->requirePermission('news.delete')) {
            return redirect()->back();
        }

        // we get the partner
        if (!$news = $this->repository->find($request->get('_id'))) {
            \Modal::alert([
                trans('news.message.find.failure'),
            ], 'error');

            return redirect()->back();
        }

        try {
            // we remove the partner logo
            if ($news->image) {
                \ImageManager::remove(
                    $news->image,
                    $news->storagePath(),
                    $news->availableSizes('image')
                );
            }

            // we delete the role
            $news->delete();

            \Modal::alert([
                trans('news.message.delete.success', ['title' => $news->title]),
            ], 'success');

            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error($e);
            \Modal::alert([
                trans('news.message.delete.failure', ['title' => $news->title]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function activate(Request $request)
    {
        // we check the current user permission
        $permission = 'news.update';
        if (!\Sentinel::getUser()->hasAccess([$permission])) {
            return response([
                trans('permissions.message.access.denied') . " : <b>" .
                trans('permissions.' . $permission) . "</b>",
            ], 400);
        }

        // we get the model
        if (!$news = $this->repository->find($request->get('id'))) {
            return response([
                trans('news.message.find.failure', ['id' => $request->get('id')]),
            ], 401);
        }

        // we convert the "on" value to the activation order to a boolean value
        $request->merge([
            'activation_order' => filter_var($request->get('activation_order'), FILTER_VALIDATE_BOOLEAN),
        ]);

        // we check the inputs validity
        $errors = [];
        $validator = \Validator::make($request->all(), [
            'id'               => 'required|exists:news,id',
            'activation_order' => 'required|boolean',
        ]);
        foreach ($validator->errors()->all() as $error) {
            $errors[] = $error;
        }
        // if errors are found
        if (count($errors)) {
            return response([
                $errors,
            ], 400);
        }

        try {
            $news->active = $request->activation_order;
            $news->save();

            return response([
                trans('news.message.activation.success', ['title' => $news->title]),
            ], 200);
        } catch (\Exception $e) {
            \Log::error($e);

            return response([
                trans('news.message.activation.failure', ['title' => $news->title]),
            ], 401);
        }
    }

}
