<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Repositories\News\NewsRepositoryInterface;
use App\Repositories\Slide\SlideRepositoryInterface;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    private $news;
    private $slide;

    /**
     * Create a new home controller instance.
     *
     * @return void
     */
    public function __construct(NewsRepositoryInterface $news, SlideRepositoryInterface $slide)
    {
        parent::__construct();
        $this->news = $news;
        $this->slide = $slide;
    }

    public function edit(Request $request)
    {
        // we check the current user permission
        $required = 'home.edit';
        if (!\Sentinel::getUser()->hasAccess([$required])) {
            \Modal::alert([
                trans('permissions.message.access.denied') . " : <b>" . trans('permissions.' . $required) . "</b>",
            ], 'error');

            return redirect()->back();
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.home.edit');

        // we define the slides table list columns
        $columns = [
            [
                'title' => trans('home.page.label.slide.background_image'),
                'key'   => 'background_image',
                'image' => [
                    'storage_path' => $this->slide->getModel()->storagePath(),
                    'size'         => [
                        'thumbnail' => 'admin',
                        'detail'    => '767',
                    ],
                ],
            ], [
                'title' => trans('home.page.label.slide.picto'),
                'key'   => 'picto',
                'image' => [
                    'storage_path' => $this->slide->getModel()->storagePath(),
                    'size'         => [
                        'thumbnail' => 'admin',
                        'detail'    => 'picto',
                    ],
                    'class'        => 'bg-dark',
                ],
            ], [
                'title'   => trans('home.page.label.slide.title'),
                'key'     => 'title',
                'sort_by' => 'slides.title',
            ], [
                'title'     => trans('home.page.label.slide.quote'),
                'key'       => 'quote',
                'str_limit' => 50,
            ], [
                'title'           => trans('home.page.label.slide.position'),
                'key'             => 'position',
                'sort_by'         => 'slides.position',
                'sort_by_default' => true,
            ],
            [
                'title'    => trans('home.page.label.slide.activation'),
                'key'      => 'active',
                'activate' => 'slides.activate',
            ],
        ];

        // we set the routes used in the table list
        $routes = [
            'index'   => 'home.edit',
            'create'  => 'slides.create',
            'edit'    => 'slides.edit',
            'destroy' => 'slides.destroy',
        ];

        // we instantiate the query
        $query = $this->slide->getModel()->query();

        // we prepare the confirm config
        $confirm_config = [
            'action'     => trans('home.page.action.slide.delete'),
            'attributes' => ['title'],
        ];

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

        // we get the json home content
        $home = [];
        if (is_file(storage_path('app/home/content.json'))) {
            $home = json_decode(file_get_contents(storage_path('app/home/content.json')));
        }

        // prepare data for the view
        $data = [
            'seoMeta'       => $this->seoMeta,
            'title'         => isset($home->title) ? $home->title : null,
            'description'   => isset($home->description) ? $home->description : null,
            'video_link'    => isset($home->video_link) ? $home->video_link : null,
            'tableListData' => $tableListData,
        ];

        // return the view with data
        return view('pages.back.home-edit')->with($data);
    }

    /**
     * @return $this
     */
    public function show()
    {

        // SEO Meta settings
        $this->seoMeta['page_title'] = 'Accueil';
        $this->seoMeta['meta_desc'] = 'Bienvenue sur le site du club Université Nantes Aviron,
        le plus grand club d\'aviron universitaire de France, ouvert à tous les publics !';
        $this->seoMeta['meta_keywords'] = 'club, universite, nantes, aviron, sport, universitaire, etudiant, ramer';

        // we get the two last news
        $last_news = $this->news->orderBy('released_at', 'desc')->take(2)->get();

        // we get the slides
        $slides = $this->slide->orderBy('position', 'asc')->get();
        \JavaScript::put([
            'slides_count' => sizeof($slides),
        ]);

        // we get the json home content
        $home = [];
        if (is_file(storage_path('app/home/content.json'))) {
            $home = json_decode(file_get_contents(storage_path('app/home/content.json')));
        }

        // we parse the markdown content
        $parsedown = new \Parsedown();
        $description = isset($home->description) ? $parsedown->text($home->description) : null;

        // prepare data for the view
        $data = [
            'seoMeta'     => $this->seoMeta,
            'slides'      => $slides,
            'last_news'   => $last_news,
            'title'       => isset($home->title) ? $home->title : null,
            'description' => $description,
            'video_link'  => isset($home->video_link) ? $home->video_link : null,
            'css'         => url(elixir('css/app.home.css')),
            'js'          => url(elixir('js/app.home.js')),
        ];

        // return the view with data
        return view('pages.front.home')->with($data);
    }

    public function update(Request $request)
    {
        // we check the current user permission
        $required = 'home.update';
        if (!\Sentinel::getUser()->hasAccess([$required])) {
            \Modal::alert([
                trans('permissions.message.access.denied') . " : <b>" . trans('permissions.' . $required) . "</b>",
            ], 'error');

            return redirect()->back();
        }

        // we check the inputs
        $errors = [];
        $validator = \Validator::make($request->all(), [
            'title'       => 'string',
            'description' => 'string',
            'video_link'  => 'url',
        ]);
        foreach ($validator->errors()->all() as $error) {
            $errors[] = $error;
        }
        // if errors are found
        if (count($errors)) {
            // we flash the request
            $request->flash();

            // we notify the current user
            \Modal::alert($errors, 'error');

            return redirect()->back();
        }

        try {
            // we store the content into a json file
            file_put_contents(storage_path('app/home/content.json'), json_encode($request->except('_token', '_method')));

            \Modal::alert([
                trans('home.message.update.success'),
            ], 'success');

            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error($e);
            \Modal::alert([
                trans('home.message.update.failure'),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }

    }

}
