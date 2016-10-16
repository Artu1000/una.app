<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Repositories\Slide\SlideRepositoryInterface;
use CustomLog;
use Exception;
use Illuminate\Http\Request;
use ImageManager;
use InputSanitizer;
use Modal;
use Permission;
use Sentinel;
use Validation;

class SlidesController extends Controller
{
    /**
     * Create a new home controller instance.
     *
     * @return void
     */
    public function __construct(SlideRepositoryInterface $slide)
    {
        parent::__construct();
        $this->repository = $slide;
    }

    public function create()
    {
        // we check the current user permission
        if (!Permission::hasPermission('home.slides.create')) {
            // we redirect the current user to the user list if he has the required permission
            if (Sentinel::getUser()->hasAccess('home.page.view')) {
                return redirect()->route('home.page.edit');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }

        // SEO Meta settings
        $this->seo_meta['page_title'] = trans('seo.back.home.slides.create');

        // we get the slide list
        $slide_list = $this->repository->orderBy('position', 'asc')->get();

        // we prepare the master role status and we add at the beginning of the role list
        $first_slide = new \stdClass();
        $first_slide->id = 0;
        $first_slide->title = trans('home.page.label.slide.first');
        $slide_list->prepend($first_slide);

        // prepare data for the view
        $data = [
            'seo_meta'   => $this->seo_meta,
            'slide_list' => $slide_list,
        ];

        // return the view with data
        return view('pages.back.home-slide-edit')->with($data);
    }

    public function store(Request $request)
    {
        // we check the current user permission
        if (!Permission::hasPermission('home.slides.create')) {
            // we redirect the current user to the user list if he has the required permission
            if (Sentinel::getUser()->hasAccess('home.page.view')) {
                return redirect()->route('home.page.edit');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }

        // if the active field is not given, we set it to false
        $request->merge(['active' => $request->get('active', false)]);

        // we sanitize the entries
        $request->replace(InputSanitizer::sanitize($request->all()));

        // we check inputs validity
        $rules = [
            'title'            => 'required|string',
            'url'              => 'url',
            'quote'            => 'required|string|min:50|max:150',
            'picto'            => 'image|mimes:png|image_size:>=300,>=300',
            'background_image' => 'image|mimes:jpg,jpeg|image_size:>=2560,>=1440',
            'active'           => 'required|boolean',
        ];
        if ($request->get('previous_slide_id') === 0) {
            $rules['previous_slide_id'] = 'numeric';
        } else {
            $rules['previous_slide_id'] = 'required|numeric|exists:slides,id';
        }

        if (!Validation::check($request->all(), $rules)) {
            // we flash the request
            $request->flashExcept('picto', 'background_image');

            return redirect()->back();
        }

        try {
            // we update the roles hierarchy
            $new_position = $this->repository->updatePositions($request->get('previous_slide_id'));

            // we create the role
            $slide = $this->repository->create([
                'title'    => $request->get('title'),
                'url'      => $request->get('url'),
                'quote'    => $request->get('quote'),
                'position' => $new_position,
                'active'   => $request->get('active'),
            ]);

            // we store the picto file
            if ($picto = $request->file('picto')) {
                // we optimize, resize and save the image
                $file_name = ImageManager::optimizeAndResize(
                    $picto->getRealPath(),
                    $slide->imageName('picto'),
                    $picto->getClientOriginalExtension(),
                    $slide->storagePath(),
                    $slide->availableSizes('picto')
                );
                // we save the picto names
                $slide->picto = $file_name;
                $slide->save();
            }

            // we store the background image file
            if ($background_image = $request->file('background_image')) {
                // we optimize, resize and save the image
                $file_name = ImageManager::optimizeAndResize(
                    $background_image->getRealPath(),
                    $slide->imageName('background_image'),
                    $background_image->getClientOriginalExtension(),
                    $slide->storagePath(),
                    $slide->availableSizes('background_image')
                );
                // we save the picto name
                $slide->background_image = $file_name;
                $slide->save();
            }

            // we sanitize the roles positions
            $this->repository->sanitizePositions();

            Modal::alert([
                trans('home.message.slide.creation.success', ['slide' => $slide->name]),
            ], 'success');

            return redirect(route('home.page.edit'));
        } catch (Exception $e) {
            // we flash the request
            $request->flashExcept('picto', 'background_image');

            // we log the error
            CustomLog::error($e);

            // we notify the current user
            Modal::alert([
                trans('home.message.slide.creation.failure', ['slide' => $request->get('name')]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }
    }

    public function edit($id)
    {
        // we check the current user permission
        if (!Permission::hasPermission('home.slides.view')) {
            // we redirect the current user to the home edit page if he has the required permission
            if (Sentinel::getUser()->hasAccess('home.page.view')) {
                return redirect()->route('home.page.edit');
            } else {
                // or we redirect the current user to the dashboard page
                return redirect()->route('dashboard.index');
            }
        }

        // we get the slide
        try {
            $slide = $this->repository->find($id);
        } catch (Exception $e) {
            // we log the error
            CustomLog::error($e);

            // we notify the current user
            Modal::alert([
                trans('home.message.slide.find.failure', ['id' => $id]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            // we redirect the current user to the home edit page if he has the required permission
            if (Sentinel::getUser()->hasAccess('home.view')) {
                return redirect()->route('home.edit');
            } else {
                // or we redirect the current user to the dashboard page
                return redirect()->route('dashboard.index');
            }
        }

        // SEO Meta settings
        $this->seo_meta['page_title'] = trans('seo.back.home.slides.edit');

        // we get the slide list without the current
        $slide_list = $this->repository->orderBy('position', 'asc')->where('id', '<>', $id)->get();

        // we get the parent role of the current role
        $previous_slide = $slide->position > 1 ? $this->repository->where(
            'position',
            $slide->position - 1
        )->first() : null;

        // we prepare the master role status and we add at the beginning of the role list
        $first_slide = new \stdClass();
        $first_slide->id = 0;
        $first_slide->title = trans('home.page.label.slide.first');
        $slide_list->prepend($first_slide);

        // we prepare the data for breadcrumbs
        $breadcrumbs_data = [
            'slide' => $slide,
        ];

        // prepare data for the view
        $data = [
            'seo_meta'         => $this->seo_meta,
            'slide'            => $slide,
            'previous_slide'   => $previous_slide,
            'slide_list'       => $slide_list,
            'breadcrumbs_data' => $breadcrumbs_data,
        ];

        // return the view with data
        return view('pages.back.home-slide-edit')->with($data);
    }

    public function update($id, Request $request)
    {
        // we get the slide
        try {
            $slide = $this->repository->find($id);
        } catch (Exception $e) {
            // we log the error
            CustomLog::error($e);

            // we notify the current user
            Modal::alert([
                trans('home.message.slide.find.failure', ['id' => $id]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            // we redirect the current user to the home edit page if he has the required permission
            if (Sentinel::getUser()->hasAccess('home.view')) {
                return redirect()->route('home.edit');
            } else {
                // or we redirect the current user to the dashboard page
                return redirect()->route('dashboard.index');
            }
        }

        // we check the current user permission
        if (!Permission::hasPermission('home.slides.update')) {
            // we redirect the current user to the user list if he has the required permission
            if (Sentinel::getUser()->hasAccess('home.slides.view')) {
                return redirect()->route('home.slides.edit', ['id' => $id]);
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }

        // if the active field is not given, we set it to false
        $request->merge(['active' => $request->get('active', false)]);

        // we sanitize the entries
        $request->replace(InputSanitizer::sanitize($request->all()));

        // we set the verification rules
        $rules = [
            '_id'              => 'numeric|exists:slides,id',
            'title'            => 'required|string',
            'url'              => 'active_url',
            'quote'            => 'required|string|min:50|max:150',
            'picto'            => 'image|mimes:png|image_size:>=300,>=300',
            'background_image' => 'image|mimes:jpg,jpeg|image_size:>=2560,>=1440',
            'active'           => 'required|boolean',
        ];
        if ($request->get('previous_slide_id') === 0) {
            $rules ['previous_slide_id'] = 'numeric';
        } else {
            $rules ['previous_slide_id'] = 'required|numeric|exists:slides,id';
        }

        if (!Validation::check($request->all(), $rules)) {
            // we flash the request
            $request->flashExcept('picto', 'background_image');

            return redirect()->back();
        }

        try {
            // we update the roles hierarchy
            $new_position = $this->repository->updatePositions($request->get('previous_slide_id'));

            // we update the slide
            $slide->update([
                'title'    => $request->get('title'),
                'url'      => $request->get('url'),
                'quote'    => $request->get('quote'),
                'position' => $new_position,
                'active'   => $request->get('active'),
            ]);

            // we store the picto file
            if ($picto = $request->file('picto')) {
                // we optimize, resize and save the image
                $file_name = ImageManager::optimizeAndResize(
                    $picto->getRealPath(),
                    $slide->imageName('picto'),
                    $picto->getClientOriginalExtension(),
                    $slide->storagePath(),
                    $slide->availableSizes('picto')
                );
                // we save the picto name
                $slide->picto = $file_name;
                $slide->save();
            }

            // we store the background image file
            if ($background_image = $request->file('background_image')) {
                // we optimize, resize and save the image
                $file_name = ImageManager::optimizeAndResize(
                    $background_image->getRealPath(),
                    $slide->imageName('background_image'),
                    $background_image->getClientOriginalExtension(),
                    $slide->storagePath(),
                    $slide->availableSizes('background_image')
                );
                // we save the background image name
                $slide->background_image = $file_name;
                $slide->save();
            }

            // we sanitize the roles ranks
            $this->repository->sanitizePositions();

            Modal::alert([
                trans('home.message.slide.update.success', ['slide' => $slide->name]),
            ], 'success');

            return redirect()->back();
        } catch (Exception $e) {
            // we flash the request
            $request->flashExcept('picto', 'background_image');

            // we log the error
            CustomLog::error($e);

            // we notify the current user
            Modal::alert([
                trans('home.message.slide.update.failure', ['slide' => $request->get('name')]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }
    }

    public function destroy($id, Request $request)
    {
        // we check the current user permission
        if (!Permission::hasPermission('home.slides.delete')) {
            // we redirect the current user to the permissions list if he has the required permission
            if (Sentinel::getUser()->hasAccess('home.page.view')) {
                return redirect()->route('home.page.edit');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }

        // we get the slide
        try {
            $slide = $this->repository->find($id);
        } catch (Exception $e) {
            // we log the error
            CustomLog::error($e);

            // we notify the current user
            Modal::alert([
                trans('home.message.slide.find.failure', ['id' => $id]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            // we redirect the current user to the home edit page if he has the required permission
            if (Sentinel::getUser()->hasAccess('home.page.view')) {
                return redirect()->route('home.page.edit');
            } else {
                // or we redirect the current user to the dashboard page
                return redirect()->route('dashboard.index');
            }
        }

        // we delete the role
        try {
            // we remove the slide picto & background image
            if ($slide->picto) {
                ImageManager::remove(
                    $slide->picto,
                    $slide->storagePath(),
                    $slide->availableSizes('picto')
                );
            }
            if ($slide->background_image) {
                ImageManager::remove(
                    $slide->background_image,
                    $slide->storagePath(),
                    $slide->availableSizes('background_image')
                );
            }

            Modal::alert([
                trans('home.message.slide.delete.success', ['slide' => $slide->title]),
            ], 'success');

            // we delete the slide
            $slide->delete();

            // we sanitize the roles ranks
            $this->repository->sanitizePositions();

            return redirect()->back();
        } catch (Exception $e) {
            // we log the error
            CustomLog::error($e);
            
            Modal::alert([
                trans('permissions.message.delete.failure', ['title' => $slide->title]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }
    }

    public function activate($id, Request $request)
    {
        // we get the slide
        try {
            $slide = $this->repository->find($id);
        } catch (Exception $e) {
            // we log the error
            CustomLog::error($e);

            return response([
                'message' => [
                    trans('home.message.slide.find.failure', ['id' => $id]),
                    trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
                ],
            ], 401);
        }

        // we check the current user permission
        if ($permission_denied = Permission::hasPermissionJson('home.slides.update')) {
            return response([
                'active'  => $slide->active,
                'message' => [$permission_denied],
            ], 401);
        }

        // if the active field is not given, we set it to false
        $request->merge(['active' => $request->get('active', false)]);

        // we sanitize the entries
        $request->replace(\InputSanitizer::sanitize($request->all()));

        // we check inputs validity
        $rules = [
            'active' => 'required|boolean',
        ];
        if (is_array($errors = Validation::check($request->all(), $rules, true))) {
            return response([
                'active'  => $slide->active,
                'message' => $errors,
            ], 401);
        }

        try {
            $slide->active = $request->get('active');
            $slide->save();

            return response([
                'active'  => $slide->active,
                'message' => [
                    trans('home.message.slide.activation.success.label', ['action' => trans_choice('users.message.activation.success.action', $slide->active), 'slide' => $slide->title]),
                ],
            ], 200);
        } catch (\Exception $e) {
            // we log the error
            CustomLog::error($e);

            return response([
                'active'  => $slide->fresh()->active,
                'message' => [
                    trans('home.message.slide.activation.failure', ['slide' => $slide->title]),
                    trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email'),]),
                ],
            ], 401);
        }
    }

}
