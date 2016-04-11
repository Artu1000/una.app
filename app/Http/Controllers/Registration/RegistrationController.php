<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Repositories\RegistrationPrice\RegistrationPriceRepositoryInterface;
use CustomLog;
use Entry;
use ImageManager;
use Modal;
use Permission;
use Request;
use Sentinel;
use Validation;


class RegistrationController extends Controller
{

    /**
     * Create a new home controller instance.
     *
     * @return void
     */
    public function __construct(RegistrationPriceRepositoryInterface $price)
    {
        parent::__construct();
        $this->repository = $price;
    }

    /**
     * @return $this
     */
    public function index()
    {
        // SEO Meta settings
        $this->seoMeta['page_title'] = 'Inscription';
        $this->seoMeta['meta_desc'] = 'Tarifs, modalités, catégories, ... Récupérez toutes les informations nécessaire
        afin de procéder à votre inscription au club Université Nantes Aviron (UNA)';
        $this->seoMeta['meta_keywords'] = 'club, université, nantes, aviron, inscription, tarif, categorie, rameur';

        $prices = $this->repository->orderBy('price', 'asc')->get();

        // prepare data for the view
        $data = [
            'seoMeta' => $this->seoMeta,
            'prices'  => $prices,
            'css'     => url(elixir('css/app.registration.css')),
        ];

        // return the view with data
        return view('pages.front.registration')->with($data);
    }

    /**
     * @return mixed
     */
    public function pageEdit()
    {
        // we check the current user permission
        if (!Permission::hasPermission('registration.page.view')) {
            return redirect()->route('dashboard.index');
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.back.registration.page_edit');

        // we get the json home content
        $registration_page = null;
        if (is_file(storage_path('app/registration/content.json'))) {
            $registration_page = json_decode(file_get_contents(storage_path('app/registration/content.json')));
        }

        // prepare data for the view
        $data = [
            'seoMeta'          => $this->seoMeta,
            'title'            => isset($registration_page->title) ? $registration_page->title : null,
            'background_image' => isset($registration_page->background_image) ? $registration_page->background_image : null,
            'description'      => isset($registration_page->description) ? $registration_page->description : null,
//            'tableListData' => $tableListData,
        ];

        // return the view with data
        return view('pages.back.registration-page-edit')->with($data);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function pageStore(Request $request)
    {
        // we check the current user permission
        if (!Permission::hasPermission('registration.page.update')) {
            // we redirect the current user to the registration page view if he has the required permission
            if (Sentinel::getUser()->hasAccess('registration.page.view')) {
                return redirect()->route('registration.page_edit');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }

        // we get the json registration content
        $registration = null;
        if (is_file(storage_path('app/registration/content.json'))) {
            $registration = json_decode(file_get_contents(storage_path('app/registration/content.json')));
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
            $inputs = $request->except('_token', '_method', 'background_image');

            // we store the background image file
            if ($background_image = $request->file('background_image')) {
                // we optimize, resize and save the image
                $file_name = ImageManager::optimizeAndResize(
                    $background_image->getRealPath(),
                    config('image.registration.background_image.name'),
                    $background_image->getClientOriginalExtension(),
                    config('image.registration.storage_path'),
                    config('image.registration.background_image.sizes')
                );
                // we set the file name
                $inputs['background_image'] = $file_name;
            } elseif ($request->get('remove_background_image')) {
                // we remove the background image
                if (isset($registration->background_image)) {
                    ImageManager::remove(
                        $registration->background_image,
                        config('image.registration.storage_path'),
                        config('image.registration.background_image.sizes')
                    );
                }
                $inputs['background_image'] = null;
            } else {
                $inputs['background_image'] = isset($registration->background_image) ? $registration->background_image : null;
            }

            // we store the content into a json file
            file_put_contents(
                storage_path('app/registration/content.json'),
                json_encode($inputs)
            );

            Modal::alert([
                trans('registration.message.content_update.success', ['title' => $request->get('title')]),
            ], 'success');

            return redirect()->back();
        } catch (\Exception $e) {

            // we flash the request
            $request->flashExcept('background_image');

            // we log the error
            CustomLog::error($e);

            // we notify the current user
            Modal::alert([
                trans('registration.message.content_update.failure', ['title' => isset($registration->title) ? $registration->title : null]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }
    }

}
