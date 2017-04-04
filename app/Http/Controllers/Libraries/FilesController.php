<?php

namespace App\Http\Controllers\Libraries;

use App\Http\Controllers\Controller;
use App\Models\LibraryFile;
use CustomLog;
use Exception;
use FileManager;
use Illuminate\Http\Request;
use InputSanitizer;
use JavaScript;
use Lang;
use Modal;
use Permission;
use Sentinel;
use TableList;
use Validation;

class FilesController extends Controller
{
    /**
     * FilesController constructor.
     * @param LibraryFile $media
     */
    public function __construct(LibraryFile $media)
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
        if (!Permission::hasPermission('libraries.files.list')) {
            return redirect()->route('backoffice.index');
        }
        
        // SEO Meta settings
        $this->seo_meta['page_title'] = trans('seo.back.libraries.files.index');
        
        // we define the table list columns
        $columns = [
            [
                'title' => trans('libraries.files.page.label.file'),
                'key'   => 'src',
                'file'  => [
                    'icon'         => '<i class="fa fa-file-o" aria-hidden="true"></i>',
                    'storage_path' => $this->repository->getModel()->storagePath(),
                ],
            ],
            [
                'title'   => trans('libraries.files.page.label.src'),
                'key'     => 'src',
                'sort_by' => 'library_files.src',
            ],
            [
                'title'   => trans('libraries.files.page.label.alias'),
                'key'     => 'alias',
                'sort_by' => 'library_files.alias',
                'input'   => [
                    'type'   => 'text',
                    'class'  => 'submit-on-change',
                    'submit' => [
                        'method' => 'POST',
                        'update' => true,
                        'route'  => 'libraries.files.update',
                    ],
                ],
            ],
            [
                'title'           => trans('libraries.files.page.label.created_at'),
                'key'             => 'created_at',
                'sort_by'         => 'library_files.created_at',
                'sort_by_default' => 'desc',
                'date'            => 'd/m/Y H:i',
            ],
        ];
        
        
        // we set the routes used in the table list
        $routes = [
            'index'   => [
                'route'  => 'libraries.files.index',
                'params' => [],
            ],
            'destroy' => [
                'route'  => 'libraries.files.destroy',
                'params' => [],
            ],
        ];
        
        // we instantiate the query
        $query = $this->repository->getModel()->query();
        
        // we prepare the confirm config
        $confirm_config = [
            'action'     => trans('libraries.files.page.action.delete'),
            'attributes' => ['src'],
        ];
        
        // we prepare the search config
        $search_config = [
            [
                'key'      => trans('libraries.files.page.label.alias'),
                'database' => 'library_files.alias',
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
            $success = Lang::choice('libraries.files.message.file.success.message', $count, ['count' => $count]);
        }
        $errors = null;
        if ($rejected = $request->rejected) {
            $decoded_rejected = json_decode($rejected);
            $count = count($decoded_rejected);
            $errors = Lang::choice('libraries.files.message.file.error.title', $count, ['count' => $count]);
            foreach ($decoded_rejected as $file) {
                $errors .= '<br/><span class="text-danger"><i class="fa fa-hand-o-right" aria-hidden="true"></i></span> ' . trans('libraries.files.message.file.error.detail', ['name' => $file->name, 'error' => $file->message]);
            };
        }
        if ($success && !$errors) {
            Modal::alert([
                trans('libraries.files.message.file.success.congratulations') . ' : ' . $success,
            ], 'success');
        } elseif ($success && $errors) {
            Modal::alert([
                $success . ', ' . trans('libraries.files.message.file.error.however') . ' ' . $errors,
            ], 'error');
        } elseif (!$success && $errors) {
            Modal::alert([
                trans('libraries.files.message.file.error.beware') . ' ' . $errors,
            ], 'error');
        }
        
        JavaScript::put([
            'reload_route'      => route('libraries.files.index'),
            'invalid_file_type' => trans('libraries.files.message.file.type'),
            'file_too_big'      => trans('libraries.files.message.file.size'),
        ]);
        
        // we manage the non translated data
        $data = [
            'tableListData' => $tableListData,
            'seo_meta'      => $this->seo_meta,
        ];
        
        // return the view with data
        return view('pages.back.library-files-list')->with($data);
    }
    
    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        // we check the current user permission
        if ($permission_denied = Permission::hasPermissionJson('libraries.files.create')) {
            return response([
                $permission_denied . '.',
            ], 401);
        }
        
        // we set the validity rules
        $rules = [
            'file' => 'required|mimes:' . implode(',', str_replace('.', '', config('libraries.files.accepted_extensions'))),
        ];
        if (is_array($errors = Validation::check($request->all(), $rules, true))) {
            return response($errors[0], 401);
        }
        
        try {
            // we store the file
            if ($file = $request->file('file')) {
                // we create the file
                $db_file = $this->repository->create([
                    'src'   => $file->getRealPath(),
                    'alias' => $file->getRealPath(),
                ]);
                // we store the file
                $file_name = FileManager::storeAndRename(
                    $file->getRealPath(),
                    $this->repository->getModel()->fileName(),
                    $file->getClientOriginalExtension(),
                    $db_file->storagePath()
                );
                // we update the file source
                $db_file->src = $file_name;
                $db_file->alias = str_slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
                $db_file->save();
                
                return response($db_file->src, 200);
            }
        } catch (Exception $e) {
            // we log the error
            CustomLog::error($e);
            
            // we notify the current user
            return response([
                trans('libraries.files.message.create.failure', ['file' => $request->file('file')->getClientOriginalName()]) .
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
        // we get the file
        try {
            $file = $this->repository->find($id);
        } catch (Exception $e) {
            
            // we notify the current user
            Modal::alert([
                trans('libraries.files.message.find.failure', ['id' => $id]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');
            
            return redirect()->back();
        }
        
        // we check the current user permission
        if ($permission_denied = Permission::hasPermissionJson('libraries.files.update')) {
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
            'value' => 'alpha_dash|unique:library_files,alias',
        ];
        if (is_array($errors = Validation::check($request->all(), $rules, true))) {
            return response([
                'value'   => $file->alias,
                'message' => $errors,
            ], 401);
        }
        
        try {
            $file->alias = $request->value;
            $file->save();
            
            return response([
                'value'   => $file->alias,
                'message' => [
                    trans('libraries.files.message.update.success', ['file' => $file->alias]),
                ],
            ], 200);
        } catch (Exception $e) {
            // we log the error
            CustomLog::error($e);
            
            return response([
                'value'   => $file->alias,
                'message' => [
                    trans('libraries.files.message.update.failure', ['file' => $file->alias]),
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
        // we get the file
        try {
            $file = $this->repository->find($id);
        } catch (Exception $e) {
            
            // we notify the current user
            Modal::alert([
                trans('libraries.files.message.find.failure', ['id' => $id]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');
            
            return redirect()->back();
        }
        
        // we check the current user permission
        if (!Permission::hasPermission('libraries.files.delete')) {
            // we redirect the current user to the files list if he has the required permission
            if (Sentinel::getUser()->hasAccess('libraries.files.page.view')) {
                return redirect()->route('libraries.files.page.edit');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('backoffice.index');
            }
        }
        
        try {
            // we remove the news file
            if ($file->src) {
                FileManager::remove(
                    $file->src,
                    $file->storagePath()
                );
            }
            
            // we delete the role
            $file->delete();
            
            Modal::alert([
                trans('libraries.files.message.delete.success', ['file' => $file->src]),
            ], 'success');
            
            return redirect()->route('libraries.files.index');
        } catch (Exception $e) {
            // we log the error
            CustomLog::error($e);
            
            // we notify the current user
            Modal::alert([
                trans('libraries.files.message.delete.failure', ['file' => $file->src]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');
            
            return redirect()->route('libraries.files.index');
        }
    }
    
}
