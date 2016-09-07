<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Repositories\Registration\RegistrationPriceRepositoryInterface;
use CustomLog;
use Entry;
use Exception;
use FileManager;
use Illuminate\Http\Request;
use ImageManager;
use Modal;
use Parsedown;
use Permission;
use Sentinel;
use TableList;
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
        $this->seo_meta['page_title'] = trans('seo.front.registration.title');
        $this->seo_meta['meta_desc'] = trans('seo.front.registration.description');
        $this->seo_meta['meta_keywords'] = trans('seo.front.registration.keywords');
        
        // og meta settings
        $this->og_meta['og:title'] = trans('seo.front.registration.title');
        $this->og_meta['og:description'] = trans('seo.front.registration.description');
        $this->og_meta['og:type'] = 'article';
        $this->og_meta['og:url'] = route('registration.index');
        
        // we get the registration prices
        $prices = $this->repository->where('active', true)->orderBy('price', 'asc')->get();
        
        // we get the json registration content
        $registration_page = null;
        if (is_file(storage_path('app/registration/content.json'))) {
            $registration_page = json_decode(file_get_contents(storage_path('app/registration/content.json')));
        }
        
        // we parse the markdown content
        $parsedown = new Parsedown();
        $description = isset($registration_page->description) ? $parsedown->text($registration_page->description) : null;
        // we replace the images aliases by real paths
        $description = ImageManager::replaceLibraryImagesAliasesByRealPath($description);
        // we replace the files aliases by real paths
        $description = FileManager::replaceLibraryFilesAliasesByRealPath($description);
        
        // prepare data for the view
        $data = [
            'seo_meta'               => $this->seo_meta,
            'og_meta'                => $this->og_meta,
            'prices'                 => $prices,
            'title'                  => isset($registration_page->title) ? $registration_page->title : null,
            'registration_form_file' => isset($registration_page->registration_form_file) ? $registration_page->registration_form_file : null,
            'background_image'       => isset($registration_page->background_image) ? $registration_page->background_image : null,
            'description'            => $description,
            'css'                    => elixir('css/app.registration.css'),
        ];
        
        // return the view with data
        return view('pages.front.registration')->with($data);
    }
    
    /**
     * @return mixed
     */
    public function pageEdit(Request $request)
    {
        // we check the current user permission
        if (!Permission::hasPermission('registration.page.view')) {
            return redirect()->route('dashboard.index');
        }
        
        // SEO Meta settings
        $this->seo_meta['page_title'] = trans('seo.back.registration.page.edit');
        
        // we define the table list columns
        $columns = [
            [
                'title'   => trans('registration.page.label.price.label'),
                'key'     => 'label',
                'sort_by' => 'registration_prices.label',
            ],
            [
                'title'           => trans('registration.page.label.price.price'),
                'key'             => 'price',
                'sort_by'         => 'registration_prices.price',
                'sort_by_default' => 'asc',
            ],
            [
                'title'    => trans('registration.page.label.price.activation'),
                'key'      => 'active',
                'activate' => [
                    'route'  => 'registration.prices.activate',
                    'params' => [],
                ],
            ],
        ];
        
        // we set the routes used in the table list
        $routes = [
            'index'   => [
                'route'  => 'registration.page.edit',
                'params' => [],
            ],
            'create'  => [
                'route'  => 'registration.prices.create',
                'params' => [],
            ],
            'edit'    => [
                'route'  => 'registration.prices.edit',
                'params' => [],
            ],
            'destroy' => [
                'route'  => 'registration.prices.destroy',
                'params' => [],
            ],
        ];
        
        // we instantiate the query
        $query = app(RegistrationPriceRepositoryInterface::class)->getModel()->query();
        
        // we prepare the confirm config
        $confirm_config = [
            'action'     => trans('registration.page.action.price.delete'),
            'attributes' => ['label'],
        ];
        
        // we prepare the search config
        $search_config = [
            [
                'key'      => trans('registration.page.label.price.label'),
                'database' => 'registration_prices.label',
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
        
        // we get the json home content
        $registration_page = null;
        if (is_file(storage_path('app/registration/content.json'))) {
            $registration_page = json_decode(file_get_contents(storage_path('app/registration/content.json')));
        }
        
        // prepare data for the view
        $data = [
            'seo_meta'               => $this->seo_meta,
            'title'                  => isset($registration_page->title) ? $registration_page->title : null,
            'background_image'       => isset($registration_page->background_image) ? $registration_page->background_image : null,
            'registration_form_file' => isset($registration_page->registration_form_file) ? $registration_page->registration_form_file : null,
            'description'            => isset($registration_page->description) ? $registration_page->description : null,
            'tableListData'          => $tableListData,
        ];
        
        // return the view with data
        return view('pages.back.registration-page-edit')->with($data);
    }
    
    /**
     * @param Request $request
     * @return mixed
     */
    public function pageUpdate(Request $request)
    {
        // we check the current user permission
        if (!Permission::hasPermission('registration.page.update')) {
            // we redirect the current user to the registration page view if he has the required permission
            if (Sentinel::getUser()->hasAccess('registration.page.view')) {
                return redirect()->route('registration.page.edit');
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
            'registration_form_file'  => 'mimes:pdf',
        ];
        // we check the inputs validity
        if (!Validation::check($request->all(), $rules)) {
            // we flash the request
            $request->flashExcept('background_image', 'registration_form_file');
            
            return redirect()->back();
        }
        
//        try {
            $inputs = $request->except('_token', '_method', 'background_image', 'remove_background_image');
            
            // we store the background image file
            if ($background_image = $request->file('background_image')) {
                // we remove the background image
                if($registration->background_image){
                    ImageManager::remove(
                        $registration->background_image,
                        config('image.registration.storage_path'),
                        config('image.registration.background_image.sizes')
                    );
                }
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
            
            // we store the registration form file
            if ($registration_form_file = $request->file('registration_form_file')) {
                // we remove the registration form file
                if($registration->registration_form_file){
                    FileManager::remove(
                        $registration->registration_form_file,
                        config('file.registration.storage_path')
                    );
                }
                // we save the registration form file
                $file_name = FileManager::storeAndRename(
                    $registration_form_file->getRealPath(),
                    config('file.registration.registration_form_file.name'),
                    $registration_form_file->getClientOriginalExtension(),
                    config('file.registration.storage_path')
                );
                // we set the file name
                $inputs['registration_form_file'] = $file_name;
            } else {
                $inputs['registration_form_file'] = isset($registration->registration_form_file) ? $registration->registration_form_file : null;
            }
            
            // we store the content into a json file
            file_put_contents(
                storage_path('app/registration/content.json'),
                json_encode($inputs)
            );
            
            Modal::alert([
                trans('registration.message.content.update.success', ['title' => $request->get('title')]),
            ], 'success');
            
            return redirect()->back();
//        } catch (Exception $e) {
//
//            // we flash the request
//            $request->flashExcept('background_image', 'registration_form_file');
//
//            // we log the error
//            CustomLog::error($e);
//
//            // we notify the current user
//            Modal::alert([
//                trans('registration.message.content.update.failure', ['title' => isset($registration->title) ? $registration->title : null]),
//                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
//            ], 'error');
//
//            return redirect()->back();
//        }
    }
    
    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function activate($id, Request $request)
    {
        // we get the schedule
        try {
            $price = $this->repository->find($id);
        } catch (Exception $e) {
            // we notify the current user
            return response([
                'message' => [
                    trans('registration.message.price.find.failure', ['id' => $id]),
                    trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email'),]),
                ],
            ], 401);
        }
        
        // we check the current user permission
        if ($permission_denied = Permission::hasPermissionJson('registration.prices.update')) {
            return response([
                'active'  => $price->active,
                'message' => [$permission_denied],
            ], 401);
        }
        
        // if the active field is not given, we set it to false
        $request->merge(['active' => $request->get('active', false)]);
        
        // we sanitize the entries
        $request->replace(Entry::sanitizeAll($request->all()));
        
        // we check the inputs validity
        $rules = [
            'active' => 'required|boolean',
        ];
        if (is_array($errors = Validation::check($request->all(), $rules, true))) {
            return response([
                'active'  => $price->active,
                'message' => $errors,
            ], 401);
        }
        
        try {
            $price->active = $request->get('active');
            $price->save();
            
            return response([
                'active'  => $price->active,
                'message' => [
                    trans('registration.message.price.activation.success.label', ['action' => trans_choice('registration.message.price.activation.success.action', $price->active), 'price' => $price->label]),
                ],
            ], 200);
        } catch (Exception $e) {
            // we log the error
            CustomLog::error($e);
            
            return response([
                trans('registration.message.price.activation.failure', ['price' => $price->label]),
            ], 401);
        }
    }
}
