<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
    public function createAccount()
    {
        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.account.create.title');
        $this->seoMeta['meta_desc'] = trans('seo.account.create.description', ['site' => config('settings.app_name')]);
        $this->seoMeta['meta_keywords'] = trans('seo.account.create.keywords');

        // prepare data for the view
        $data = [
            'seoMeta' => $this->seoMeta,
            'css'     => url(elixir('css/app.login.css')),
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
        // we flash the non sensitive data
        $request->flashOnly('last_name', 'first_name', 'email');

        // we check the inputs
        $errors = [];
        $validator = \Validator::make($request->all(), [
            'last_name'  => 'required',
            'first_name' => 'required',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:6|confirmed',
        ]);
        foreach ($validator->errors()->all() as $error) {
            $errors[] = $error;
        }
        // if errors are found
        if (count($errors)) {
            \Modal::alert($errors, 'error');

            return redirect()->back();
        }

        // we create the user
        if ($user = \Sentinel::register($request->all())) {

            // we create an activation line
            $activation = \Activation::create($user);

            try {
                // we send the email asking the account activation
                \Mail::send('emails.account-activation', [
                    'user'  => $user,
                    'token' => $activation->code,
                ], function ($email) use ($user) {
                    $email->from(config('mail.from.address'), config('mail.from.name'))
                        ->to($user->email, $user->first_name . ' ' . $user->last_name)
                        ->subject(config('mail.subject.prefix') . ' ' . trans('emails.account_activation.subject'));
                });

                // notify the user & redirect
                \Modal::alert([
                    trans('auth.message.account_creation.success'),
                    trans('auth.message.activation.email.success', ['email' => $user->email]),
                ], 'success');

                return redirect(route('login.index'));

            } catch (\Exception $e) {
                \Log::error($e);
                // notify the user & redirect
                \Modal::alert([
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
        // we flash the email
        $request->flash();

        if ($user = \Sentinel::findUserByCredentials($request->only('email'))) {

            if (!$activation = \Activation::exists($user)) {
                $activation = \Activation::create($user);
            }

            try {
            // we send the activation email
            \Mail::send('emails.account-activation', [
                'user'  => $user,
                'token' => $activation->code,
            ], function ($email) use ($user) {
                $email->from(config('mail.from.address'), config('mail.from.name'))
                    ->to($user->email, $user->first_name . ' ' . $user->last_name)
                    ->subject(config('mail.subject.prefix') . ' ' . trans('emails.account_activation.subject'));
            });

            // notify the user & redirect
            \Modal::alert([
                trans('auth.message.activation.email.success', ['email' => $user->email]),
            ], 'success');

            return redirect(route('login.index'));

            } catch (\Exception $e) {
                \Log::error($e);
                // notify the user & redirect
                \Modal::alert([
                    trans('auth.message.activation.email.failure'),
                    trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
                ], 'error');

                return redirect()->back();
            }

        } else {
            \Modal::alert([
                trans('auth.message.find.failure', ['email' => $request->get('email')]),
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

        try {
            // we try to find the user from its email
            if ($user = \Sentinel::findByCredentials($request->only('email'))) {

                // we verify if the reminder token is valid
                if (\Activation::complete($user, $request->get('token'))) {
                    \Modal::alert([
                        trans('auth.message.activation.success', ['name' => $user->first_name . " " . $user->last_name]),
                    ], 'success');

                    return redirect(route('login.index'))->withInput($request->all());
                } else {
                    \Modal::alert([
                        trans('auth.message.activation.token.expired'),
                        trans('auth.message.activation.token.resend', [
                            'email' => $request->get('email'),
                            'url' => route('account.activation_email', ['email' => $request->get('email')])
                        ]),
                    ], 'error');

                    return redirect(route('login.index'))->withInput($request->all());
                }
            } else {
                // notify the user & redirect
                \Modal::alert([
                    trans('auth.message.find.failure', ['email' => $request->get('email')]),
                    trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
                ], 'error');

                return redirect(route('login.index'))->withInput($request->all());
            }
        } catch (\Exception $e) {
            \Log::error($e);
            // notify the user & redirect
            \Modal::alert([
                trans('auth.message.activation.error'),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect(route('login.index'))->withInput($request->all());
        }
    }

}
