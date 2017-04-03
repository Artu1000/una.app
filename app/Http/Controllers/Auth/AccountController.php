<?php

namespace App\Http\Controllers\Auth;

use Activation;
use App\Http\Controllers\Controller;
use CustomLog;
use Exception;
use Illuminate\Http\Request;
use ImageManager;
use Mail;
use Modal;
use Sentinel;
use Validation;

class AccountController extends Controller
{

    /**
     * Create a new home controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return $this
     */
    public function createAccount(Request $request)
    {
        // SEO Meta settings
        $this->seo_meta['page_title'] = trans('seo.front.account.create.title');
        $this->seo_meta['meta_desc'] = trans('seo.front.account.create.description', ['site' => config('settings.app_name_' . config('app.locale'))]);
        $this->seo_meta['meta_keywords'] = trans('seo.front.account.create.keywords');

        // prepare data for the view
        $data = [
            'seo_meta' => $this->seo_meta,
            'email'    => $request->get('email'),
            'css'      => elixir('css/app.auth.css'),

        ];

        // return the view with data
        return view('pages.front.account-create')->with($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        // we check the inputs
        $rules = [
            'last_name'  => 'required|string',
            'first_name' => 'required|string',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:' . config('password.min.length') . '|confirmed',
        ];
        if (!Validation::check($request->all(), $rules)) {
            // we flash the request
            $request->flash();

            return redirect()->back();
        }

        // we create the user
        if ($user = Sentinel::register($request->all())) {

            // we set the una logo as the user image
            $file_name = ImageManager::storeResizeAndRename(
                database_path('seeds/files/users/users-default-avatar.png'),
                $user->imageName('photo'),
                'png',
                $user->storagePath(),
                $user->availableSizes('photo'),
                false
            );
            // we update the user
            Sentinel::update($user, ['photo' => $file_name]);

            // we attach the user to the "User" role by default
            if (!$user_role = Sentinel::findRoleBySlug('user')) {
                // if the user role is not found, we create it
                $user_role = Sentinel::getRoleRepository()->createModel()->create([
                    'name' => 'User',
                    'slug' => 'user',
                ]);
            }
            $user_role->users()->attach($user);

            // we create an activation line
            $activation = Activation::create($user);

            try {
                // we send the email asking the account activation
                Mail::send('emails.account-activation', [
                    'user'  => $user,
                    'token' => $activation->code,
                ], function ($email) use ($user) {
                    $email->from(config('mail.from.address'), config('mail.from.name'))
                        ->to($user->email, $user->first_name . ' ' . $user->last_name)
                        ->subject(config('mail.subject.prefix') . ' ' . trans('emails.account_activation.subject'));
                });

                // notify the user & redirect
                Modal::alert([
                    trans('auth.message.account_creation.success'),
                    trans('auth.message.activation.email.success', ['email' => $user->email]),
                ], 'success');

                return redirect(route('login.index'));

            } catch (\Exception $e) {
                // we flash the request
                $request->flash();

                // we log the error
                CustomLog::error($e);

                // notify the user & redirect
                Modal::alert([
                    trans('auth.message.activation.email.failure'),
                    trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
                ], 'error');

                return redirect()->back();
            }
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function sendActivationEmail(Request $request)
    {
        // we get the user
        if (!$user = Sentinel::findUserByCredentials($request->only('email'))) {
            // we flash the request
            $request->flash();

            // we notify the current user
            Modal::alert([
                trans('auth.message.find.failure', ['email' => $request->get('email')]),
            ], 'error');

            return redirect()->route('password.index');
        }

        // we prepare the activation
        if (!$activation = Activation::exists($user)) {
            $activation = Activation::create($user);
        }

        try {
            // we send the activation email
            Mail::send('emails.account-activation', [
                'user'  => $user,
                'token' => $activation->code,
            ], function ($email) use ($user) {
                $email->from(config('mail.from.address'), config('mail.from.name'))
                    ->to($user->email, $user->first_name . ' ' . $user->last_name)
                    ->subject(config('mail.subject.prefix') . ' ' . trans('emails.account_activation.subject'));
            });

            // notify the user & redirect
            Modal::alert([
                trans('auth.message.activation.email.success', ['email' => $user->email]),
            ], 'success');

            return redirect(route('login.index'));

        } catch (Exception $e) {
            // we flash the request
            $request->flash();

            // we log the error
            CustomLog::error($e);

            // notify the user & redirect
            Modal::alert([
                trans('auth.message.activation.email.failure'),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function activateAccount(Request $request)
    {
        // we get the user
        if (!$user = Sentinel::findUserByCredentials($request->only('email'))) {
            // we flash the request
            $request->flash();

            // we notify the current user
            Modal::alert([
                trans('auth.message.find.failure', ['email' => $request->get('email')]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->route('password.index');
        }

        try {
            // we verify if the reminder token is valid
            if (Activation::complete($user, $request->get('token'))) {
                Modal::alert([
                    trans('auth.message.activation.success', ['name' => $user->first_name . " " . $user->last_name]),
                ], 'success');

                return redirect(route('login.index'))->withInput($request->all());
            } else {
                Modal::alert([
                    trans('auth.message.activation.token.expired'),
                    trans('auth.message.activation.token.resend', [
                        'email' => $request->get('email'),
                        'url'   => route('account.activation_email', ['email' => $request->get('email')]),
                    ]),
                ], 'error');

                return redirect(route('login.index'))->withInput($request->all());
            }
        } catch (\Exception $e) {
            // we log the error
            CustomLog::error($e);

            // notify the user & redirect
            Modal::alert([
                trans('auth.message.activation.error'),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect(route('login.index'))->withInput($request->all());
        }
    }

}
