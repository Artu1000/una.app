<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepositoryInterface;
use CustomLog;
use Exception;
use Illuminate\Http\Request;
use Mail;
use Modal;
use Reminder;
use Sentinel;
use Validation;

class PasswordController extends Controller
{
    public function __construct(UserRepositoryInterface $user)
    {
        parent::__construct();
        $this->repository = $user;
    }

    protected function index(Request $request)
    {
        // SEO settings
        $this->seo_meta['page_title'] = trans('seo.front.password.index.title');
        $this->seo_meta['meta_desc'] = trans('seo.front.password.index.description');
        $this->seo_meta['meta_keywords'] = trans('seo.front.password.index.keywords');

        // data send to the view
        $data = [
            'seo_meta' => $this->seo_meta,
            'email'    => $request->get('email'),
            'css'      => elixir('css/app.auth.css'),
        ];

        return view('pages.front.password-forgotten')->with($data);
    }

    protected function sendResetEmail(Request $request)
    {
        // we get the user
        if (!$user = \Sentinel::findUserByCredentials($request->only('email'))) {
            // we flash the request
            $request->flash();

            // we notify the current user
            Modal::alert([
                trans('auth.message.find.failure', ['email' => $request->get('email')]),
            ], 'error');

            return redirect()->back();
        }

        try {
            // we create a sentinel reminder for the user
            $reminder = Reminder::create($user);

            // we send the email with the reminder token
            Mail::send('emails.password-reset', [
                'user'  => $user,
                'token' => $reminder->code,
            ], function ($email) use ($user) {
                $email->from(config('mail.from.address'), config('mail.from.name'))
                    ->to($user->email, $user->first_name . ' ' . $user->last_name)
                    ->subject(config('mail.subject.prefix') . ' ' . trans('emails.password_reset.subject'));
            });

            // notify the user & redirect
            Modal::alert([
                trans('auth.message.password_reset.email.success', ['email' => $user->email]),
            ], 'success');

            return redirect(route('login.index'));

        } catch (Exception $e) {

            // we flash the request
            $request->flash();

            // we log the error
            CustomLog::error($e);

            // notify the user & redirect
            Modal::alert([
                trans('auth.message.password_reset.email.failure'),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }

    }

    public function show(Request $request)
    {
        if (!$request->get('email')) {
            // we notify the current user
            Modal::alert([
                trans('validation.required', ['attribute' => trans('validation.attributes.email')]),
            ], 'error');

            return redirect()->route('password.index');
        }

        // we get the user
        if (!$user = Sentinel::findUserByCredentials($request->only('email'))) {
            // we notify the current user
            Modal::alert([
                trans('auth.message.find.failure', ['email' => $request->get('email')]),
            ], 'error');

            return redirect()->route('password.index');
        }

        // we verify that the reminder token is valid
        if (!Reminder::exists($user, $request->only('token'))) {
            // notify the user & redirect
            Modal::alert([
                trans('auth.message.password_reset.token.expired'),
            ], 'error');

            return redirect(route('password.index'));
        }

        // SEO settings
        $this->seo_meta['page_title'] = trans('seo.front.password.show.title');
        $this->seo_meta['meta_desc'] = trans('seo.front.password.show.description');
        $this->seo_meta['meta_keywords'] = trans('seo.front.password.show.keywords');

        // data send to the view
        $data = [
            'email'    => $request->get('email'),
            'reminder' => $request->get('token'),
            'seo_meta' => $this->seo_meta,
            'css'      => elixir('css/app.auth.css'),
        ];

        return view('pages.front.password-reset')->with($data);
    }

    public function update(Request $request)
    {
        if (!$request->get('_email')) {
            // we notify the current user
            Modal::alert([
                trans('validation.required', ['attribute' => trans('validation.attributes.email')]),
            ], 'error');

            return redirect()->route('password.index');
        }

        // we get the user
        if (!$user = Sentinel::findUserByCredentials(['email' => $request->get('_email')])) {
            // we notify the current user
            Modal::alert([
                trans('auth.message.find.failure', ['email' => $request->get('_email')]),
            ], 'error');

            return redirect(route('password.index'));
        }

        // we check the inputs
        $rules = [
            'password' => 'required|min:' . config('password.min.length') . '|confirmed',
        ];
        if (!Validation::check($request->all(), $rules)) {
            // we flash the request
            $request->flash();

            return redirect()->back();
        }

        try {
            // we try to complete the password reset
            if (Reminder::complete($user, $request->get('_reminder'), $request->get('password'))) {
                // we notify the current user
                Modal::alert([
                    trans('auth.message.password_reset.success'),
                ], 'success');

                return redirect(route('login.index'));
            } else {
                // we notify the current user
                Modal::alert([
                    trans('auth.message.password_reset.token.expired'),
                ], 'error');

                return redirect(route('password.index'));
            }

        } catch (\Exception $e) {
            // we log the error
            CustomLog::error($e);

            // notify the user & redirect
            Modal::alert([
                trans('auth.message.password_reset.failure'),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }
    }

}
