<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Repositories\Slide\SlideRepositoryInterface;
use Illuminate\Http\Request;

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
        $required = 'home.slide.create';
        if (!\Sentinel::getUser()->hasAccess([$required])) {
            \Modal::alert([
                trans('permissions.message.access.denied') . " : <b>" . trans('permissions.' . $required) . "</b>",
            ], 'error');

            return redirect()->back();
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.home.slide.create');

        // we get the slide list
        $slide_list = $this->repository->orderBy('position', 'asc')->get();

        // we prepare the master role status and we add at the beginning of the role list
        $first_slide = new \stdClass();
        $first_slide->id = 0;
        $first_slide->title = trans('home.page.label.slide.first');
        $slide_list->prepend($first_slide);

        // prepare data for the view
        $data = [
            'seoMeta'    => $this->seoMeta,
            'slide_list' => $slide_list,
        ];

        // return the view with data
        return view('pages.back.slide-edit')->with($data);
    }

    public function store(Request $request)
    {
        // we check the current user permission
        $required = 'home.slide.create';
        if (!\Sentinel::getUser()->hasAccess([$required])) {
            \Modal::alert([
                trans('permissions.message.access.denied') . " : <b>" . trans('permissions.' . $required) . "</b>",
            ], 'error');

            return redirect()->back();
        }

        // we set the validation rules
        $rules = [
            'title'            => 'required|string',
            'quote'            => 'required|string',
            'picto'            => 'image|mimes:png|image_size:>=300,>=300',
            'background_image' => 'image|mimes:jpg,jpeg|image_size:>=2560,>=1440',
        ];
        if ($request->get('previous_slide_id') === '0') {
            $rules['previous_slide_id'] = 'numeric';
        } else {
            $rules['previous_slide_id'] = 'required|numeric|exists:slides,id';
        }

        // we check the inputs
        $errors = [];
        $validator = \Validator::make($request->all(), $rules);
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
            // we update the slides positions
            $new_position = $this->repository->updatePositions($request->get('previous_slide_id'));

            // we create the role
            $slide = $this->repository->create([
                'title'    => $request->get('title'),
                'quote'    => $request->get('quote'),
                'position' => $new_position,
            ]);

            // we store the picto file
            if ($picto = $request->file('picto')) {
                // we optimize, resize and save the image
                $file_name = \ImageManager::optimizeAndResize(
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
                $file_name = \ImageManager::optimizeAndResize(
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

            // we sanitize the slides positions
            $this->repository->sanitizePositions();

            \Modal::alert([
                trans('home.message.slide.create.success', ['name' => $slide->name]),
            ], 'success');

            return redirect(route('home.edit'));
        } catch (\Exception $e) {
            // we flash the request
            $request->flash();

            // we log the error and we notify the current user
            \Log::error($e);
            \Modal::alert([
                trans('home.message.slide.create.failure', ['name' => $request->get('name')]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }
    }

    public function edit($id)
    {
        // we check the current user permission
        $required = 'home.slide.view';
        if (!\Sentinel::getUser()->hasAccess([$required])) {
            \Modal::alert([
                trans('permissions.message.access.denied') . " : <b>" . trans('permissions.' . $required) . "</b>",
            ], 'error');

            return redirect()->back();
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.home.slide.edit');

        // we check if the slide exists
        if (!$slide = $this->repository->find($id)) {
            \Modal::alert([
                trans('home.message.diapo.find.failure', ['id' => $id]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }

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
            $slide->title,
        ];

        // prepare data for the view
        $data = [
            'seoMeta'          => $this->seoMeta,
            'slide'            => $slide,
            'previous_slide'   => $previous_slide,
            'slide_list'       => $slide_list,
            'breadcrumbs_data' => $breadcrumbs_data,
        ];

        // return the view with data
        return view('pages.back.slide-edit')->with($data);
    }

    public function update(Request $request)
    {
        // we check the current user permission
        $required = 'home.slide.update';
        if (!\Sentinel::getUser()->hasAccess([$required])) {
            \Modal::alert([
                trans('permissions.message.access.denied') . " : <b>" . trans('permissions.' . $required) . "</b>",
            ], 'error');

            return redirect()->back();
        }

        // we set the verification rules
        $rules = [
            '_id'              => 'numeric|exists:slides,id',
            'title'            => 'required|string',
            'quote'            => 'required|string',
            'picto'            => 'image|mimes:png|image_size:>=300,>=300',
            'background_image' => 'image|mimes:jpg,jpeg|image_size:>=2560,>=1440',
        ];
        if ($request->get('previous_slide_id') === '0') {
            $rules ['previous_slide_id'] = 'numeric';
        } else {
            $rules ['previous_slide_id'] = 'required|numeric|exists:slides,id';
        }

        // we check the inputs
        $errors = [];
        $inputs = $request->all();
        $validator = \Validator::make($inputs, $rules);
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

            $slide = $this->repository->find($request->get('_id'));

            // we store the picto file
            if ($picto = $request->file('picto')) {
                // we optimize, resize and save the image
                $file_name = \ImageManager::optimizeAndResize(
                    $picto->getRealPath(),
                    $slide->imageName('picto'),
                    $picto->getClientOriginalExtension(),
                    $slide->storagePath(),
                    $slide->availableSizes('picto')
                );
                // we save the picto name
                $inputs['picto'] = $file_name;
            }

            // we store the background image file
            if ($background_image = $request->file('background_image')) {
                // we optimize, resize and save the image
                $file_name = \ImageManager::optimizeAndResize(
                    $background_image->getRealPath(),
                    $slide->imageName('background_image'),
                    $background_image->getClientOriginalExtension(),
                    $slide->storagePath(),
                    $slide->availableSizes('background_image')
                );
                // we save the background image name
                $inputs['background_image'] = $file_name;
            }

            // we update the slides positions
            $inputs['position'] = $this->repository->updatePositions($request->get('previous_slide_id'));

            $slide->update($inputs);

            // we sanitize the roles ranks
            $this->repository->sanitizePositions();

            \Modal::alert([
                trans('home.message.slide.update.success', ['name' => $slide->name]),
            ], 'success');

            return redirect()->back();
        } catch (\Exception $e) {
            // we flash the request
            $request->flash();

            // we log the error and we notify the current user
            \Log::error($e);
            \Modal::alert([
                trans('home.message.slide.update.failure', ['name' => $request->get('name')]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }
    }

    public function destroy(Request $request)
    {
        // we check the current user permission
        $required = 'home.slide.delete';
        if (!\Sentinel::getUser()->hasAccess([$required])) {
            \Modal::alert([
                trans('permissions.message.access.denied') . " : <b>" . trans('permissions.' . $required) . "</b>",
            ], 'error');

            return redirect()->back();
        }

        // we get the diapo
        if (!$slide = $this->repository->find($request->get('_id'))) {
            \Modal::alert([
                trans('home.message.slide.find.failure', ['id' => $request->get('_id')]),
            ], 'error');

            return redirect()->back();
        }

        // we delete the role
        try {
            \Modal::alert([
                trans('home.message.slide.delete.success', ['title' => $slide->title]),
            ], 'success');

            // we delete the role
            $slide->delete();

            // we sanitize the roles ranks
            $this->repository->sanitizePositions();

            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error($e);
            \Modal::alert([
                trans('permissions.message.delete.failure', ['title' => $slide->title]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }
    }

    public function activate(Request $request)
    {
        // we check the current user permission
        $required = 'home.slide.update';
        if (!\Sentinel::getUser()->hasAccess([$required])) {
            return response([
                trans('permissions.message.access.denied') . " : <b>" . trans('permissions.' . $required) . "</b>",
            ], 401);
        }

        // we convert the "on" value to the activation order to a boolean value
        $request->merge([
            'activation_order' => filter_var($request->get('activation_order'), FILTER_VALIDATE_BOOLEAN),
        ]);

        // we check the inputs
        $errors = [];
        $validator = \Validator::make($request->all(), [
            'id'               => 'required|exists:slides,id',
            'activation_order' => 'required|boolean',
        ]);
        foreach ($validator->errors()->all() as $error) {
            $errors[] = $error;
        }
        // if errors are found
        if (count($errors)) {
            return response($errors, 401);
        }

        // we get the partner
        $slide = $this->repository->find($request->get('id'));

        try {
            $slide->active = $request->activation_order;
            $slide->save();

            return response([
                trans('partners.message.activation.success'),
            ], 200);
        } catch (\Exception $e) {
            \Log::error($e);

            return response([
                trans('partners.message.activation.failure'),
            ], 401);
        }
    }

}
