<?php

namespace App\Http\Controllers\Libraries;

use App\Http\Controllers\Controller;
use App\Models\LibraryImage;
use CustomLog;
use Exception;
use Illuminate\Http\Request;
use ImageManager;
use InputSanitizer;
use JavaScript;
use Lang;
use Modal;
use Permission;
use TableList;
use Validation;

class ImagesController extends Controller
{
    
    /**
     * NewsController constructor.
     * @param LibraryImage $media
     */
    public function __construct(LibraryImage $media)
    {
        parent::__construct();
        $this->repository = $media;
    }
    
    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        // we check the current user permission
        if (!Permission::hasPermission('libraries.images.list')) {
            return redirect()->route('backoffice.index');
        }
        
        // SEO Meta settings
        $this->seo_meta['page_title'] = trans('seo.back.libraries.images.index');
        
        // we define the table list columns
        $columns = [
            [
                'title' => trans('libraries.images.page.label.image'),
                'key'   => 'src',
                'image' => [
                    'storage_path' => $this->repository->getModel()->storagePath(),
                    'size'         => [
                        'thumbnail' => 'admin',
                        'detail'    => null,
                    ],
                ],
            ],
            [
                'title'   => trans('libraries.images.page.label.src'),
                'key'     => 'src',
                'sort_by' => 'library_images.src',
            ],
            [
                'title'   => trans('libraries.images.page.label.alias'),
                'key'     => 'alias',
                'sort_by' => 'library_images.alias',
                'input'   => [
                    'type'   => 'text',
                    'class'  => 'submit-on-change',
                    'submit' => [
                        'method' => 'POST',
                        'update' => true,
                        'route'  => 'libraries.images.update',
                    ],
                ],
            ],
            [
                'title'           => trans('libraries.images.page.label.created_at'),
                'key'             => 'created_at',
                'sort_by'         => 'library_images.created_at',
                'sort_by_default' => 'desc',
                'date'            => 'd/m/Y H:i',
            ],
        ];
        
        
        // we set the routes used in the table list
        $routes = [
            'index'   => [
                'route'  => 'libraries.images.index',
                'params' => [],
            ],
            'destroy' => [
                'route'  => 'libraries.images.destroy',
                'params' => [],
            ],
        ];
        
        // we instantiate the query
        $query = $this->repository->getModel()->query();
        
        // we prepare the confirm config
        $confirm_config = [
            'action'     => trans('libraries.images.page.action.delete'),
            'attributes' => ['src'],
        ];
        
        // we prepare the search config
        $search_config = [
            [
                'key'      => trans('libraries.images.page.label.alias'),
                'database' => 'library_images.alias',
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
        
        // we format the success and error messages and notify the current user
        $success = null;
        if ($uploaded = $request->uploaded) {
            $count = count(json_decode($uploaded));
            $success = Lang::choice('libraries.images.message.file.success.message', $count, ['count' => $count]);
        }
        $errors = null;
        if ($rejected = $request->rejected) {
            $decoded_rejected = json_decode($rejected);
            $count = count($decoded_rejected);
            $errors = Lang::choice('libraries.images.message.file.error.title', $count, ['count' => $count]);
            foreach ($decoded_rejected as $file) {
                $errors .= '<br/><span class="text-danger"><i class="fa fa-hand-o-right" aria-hidden="true"></i></span> ' . trans('libraries.images.message.file.error.detail', ['name' => $file->name, 'error' => $file->message[0]]);
            };
        }
        if ($success && !$errors) {
            Modal::alert([
                trans('libraries.images.message.file.success.congratulations') . ' : ' . $success,
            ], 'success');
        } elseif ($success && $errors) {
            dd('test');
            Modal::alert([
                $success . ', ' . trans('libraries.images.message.file.error.however') . ' ' . $errors,
            ], 'error');
        } elseif (!$success && $errors) {
            Modal::alert([
                trans('libraries.images.message.file.error.beware') . ' ' . $errors,
            ], 'error');
        }
        
        JavaScript::put([
            'reload_route'      => route('libraries.images.index'),
            'invalid_file_type' => trans('libraries.images.message.file.type'),
            'file_too_big'      => trans('libraries.images.message.file.size'),
        ]);
        
        // we manage the non translated data
        $data = [
            'tableListData' => $tableListData,
            'seo_meta'      => $this->seo_meta,
        ];
        
        // return the view with data
        return view('pages.back.library-images-list')->with($data);
    }
    
    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        // we check the current user permission
        if ($permission_denied = Permission::hasPermissionJson('libraries.images.create')) {
            return response([
                $permission_denied . '.',
            ], 401);
        }
        
        // we set the validity rules
        $rules = [
            'image' => 'image|mimes:' . implode(',', str_replace('.', '', config('libraries.images.accepted_extensions'))) . '|dimensions:min_width=40,min_height=40',
        ];
        if (is_array($errors = Validation::check($request->all(), $rules, true))) {
            return response($errors[0], 401);
        }
        
        try {
            // we store the image
            if ($img = $request->file('image')) {
                
                $image = $this->repository->create([
                    'src'   => $img->getRealPath(),
                    'alias' => $img->getRealPath(),
                ]);
                
                // we optimize, resize and save the image
                $file_name = ImageManager::storeResizeAndRename(
                    $img->getRealPath(),
                    $image->imageName('src'),
                    $img->getClientOriginalExtension(),
                    $image->storagePath(),
                    $image->availableSizes('src')
                );
                // we update the model with the image name
                $image->src = $file_name;
                $image->alias = str_slug(pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME));
                // we save the entity
                $image->save();
                
                return response($image->src, 200);
            }
        } catch (Exception $e) {
            // we log the error
            CustomLog::error($e);
            
            // we notify the current user
            return response([
                trans('libraries.images.message.create.failure', ['image' => $request->file('image')->getClientOriginalName()]) .
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]) . '.',
            ], 401);
        }
    }
    
    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function update($id, Request $request)
    {
        // we get the image
        try {
            $image = $this->repository->find($id);
        } catch (Exception $e) {
            
            // we notify the current user
            Modal::alert([
                trans('libraries.images.message.find.failure', ['id' => $id]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');
            
            return redirect()->back();
        }
        
        // we check the current user permission
        if ($permission_denied = Permission::hasPermissionJson('libraries.images.update')) {
            return response([
                'message' => [$permission_denied],
            ], 401);
        }
        
        // we replace the value by a slug string
        $request->merge(['value' => str_slug($request->value)]);
        
        // we sanitize the entries
        $request->replace(InputSanitizer::sanitize($request->all()));
        
        // we check the inputs validity
        $rules = [
            'value' => 'alpha_dash|unique:library_images,alias',
        ];
        if (is_array($errors = Validation::check($request->all(), $rules, true))) {
            return response([
                'value'   => $image->alias,
                'message' => $errors,
            ], 401);
        }
        
        try {
            $image->alias = $request->value;
            $image->save();
            
            return response([
                'value'   => $image->alias,
                'message' => [
                    trans('libraries.images.message.update.success', ['image' => $image->src]),
                ],
            ], 200);
        } catch (Exception $e) {
            // we log the error
            CustomLog::error($e);
            
            return response([
                'value'   => $image->alias,
                'message' => [
                    trans('libraries.images.message.update.failure', ['image' => $image->src]),
                    trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
                ],
            ], 401);
        }
    }
    
    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        // we get the image
        try {
            $image = $this->repository->find($id);
        } catch (Exception $e) {
            
            // we notify the current user
            Modal::alert([
                trans('libraries.images.message.find.failure', ['id' => $id]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');
            
            return redirect()->back();
        }
        
        // we check the current user permission
        if (!Permission::hasPermission('libraries.images.delete')) {
            // we redirect the current user to the images list if he has the required permission
            if (Sentinel::getUser()->hasAccess('libraries.images.page.view')) {
                return redirect()->route('libraries.images.page.edit');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('backoffice.index');
            }
        }
        
        try {
            // we remove the news image
            if ($image->src) {
                ImageManager::remove(
                    $image->src,
                    $image->storagePath(),
                    $image->availableSizes('image')
                );
            }
            
            // we delete the role
            $image->delete();
            
            Modal::alert([
                trans('libraries.images.message.delete.success', ['image' => $image->src]),
            ], 'success');
            
            return redirect()->route('libraries.images.index');
        } catch (Exception $e) {
            // we log the error
            CustomLog::error($e);
            
            // we notify the current user
            Modal::alert([
                trans('libraries.images.message.delete.failure', ['image' => $image->src]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');
            
            return redirect()->route('libraries.images.index');
        }
    }
    
}
