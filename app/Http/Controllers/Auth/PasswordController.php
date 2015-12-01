<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\Request;
use Modal;
use Sentinel;
use Validator;

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
        $this->seoMeta['page_title'] = trans('seo.password.index.title');
        $this->seoMeta['meta_desc'] = trans('seo.password.index.description');
        $this->seoMeta['meta_keywords'] = trans('seo.password.index.keywords');

        // data send to the view
        $data = [
            'seoMeta' => $this->seoMeta,
            'email'   => $request->get('email'),
            'css'     => url(elixir('css/app.login.css')),
        ];

        return view('pages.front.password-forgotten')->with($data);
    }

    protected function sendResetEmail(Request $request)
    {
        // we flash inputs
        $request->flash();

        // we check the inputs
        $errors = [];
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        foreach ($validator->errors()->all() as $error) {
            $errors[] = $error;
        }
        if (count($errors)) {
            // we notify the user
            Modal::alert($errors, 'error');

            return redirect()->back();
        }

        // we get the user from its email
        $user = $this->repository->findBy('email', $request->get('email'));

        // if a user is found
        if ($user) {
            // we create a sentinel reminder for the user
            $reminder = \Reminder::create($user);

            try {
                // we send the email with the reminder token
                \Mail::send('emails.password-reset', [
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

            } catch (\Exception $e) {
                \Log::error($e);
                // notify the user & redirect
                Modal::alert([
                    trans('auth.message.password_reset.email.failure'),
                    trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
                ], 'error');

                return redirect()->back();
            }
        } else {
            // notify the user & redirect
            Modal::alert([
                trans('auth.message.find.failure', ['email' => $request->get('email')]),
            ], 'error');

            return redirect()->back();
        }
    }

    public function show(Request $request)
    {
        // we try to find the user from its email
        if ($user = Sentinel::findByCredentials(['email' => $request->get('email')])) {
            // we verify if the reminder token is valid
            if (\Reminder::exists($user, $request->only('token'))) {

                // SEO settings
                $this->seoMeta['page_title'] = trans('seo.password.show.title');
                $this->seoMeta['meta_desc'] = trans('seo.password.show.description');
                $this->seoMeta['meta_keywords'] = trans('seo.password.show.keywords');

                // data send to the view
                $data = [
                    'email'    => $request->get('email'),
                    'reminder' => $request->get('token'),
                    'seoMeta'  => $this->seoMeta,
                    'css'      => url(elixir('css/app.login.css')),
                ];

                return view('pages.front.password-reset')->with($data);
            } else {
                // notify the user & redirect
                Modal::alert([
                    trans('auth.message.password_reset.token.expired'),
                ], 'error');

                return redirect(route('password.index'));
            }
        } else {
            // notify the user & redirect
            Modal::alert([
                trans('auth.message.find.failure', ['email' => $request->get('email')]),
            ], 'error');

            return redirect(route('password.index'));
        }
    }

    public function update(Request $request)
    {
        // we check the inputs
        $errors = [];
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6|confirmed',
        ]);
        foreach ($validator->errors()->all() as $error) {
            $errors[] = $error;
        }
        if (count($errors)) {
            // we notify the user
            Modal::alert($errors, 'error');

            return redirect()->back();
        }

        // we try to find the user from its email
        try {
            if ($user = Sentinel::findByCredentials(['email' => $request->only('_email')])) {
                // we try to complete the password reset
                if ($reminder = \Reminder::complete($user, $request->only('_reminder'), $request->get('password'))) {
                    Modal::alert([
                        trans('auth.message.password_reset.success'),
                    ], 'success');

                    return redirect(route('login.index'));
                } else {
                    Modal::alert([
                        trans('auth.message.password_reset.token.expired'),
                    ], 'error');

                    return redirect(route('password.index'));
                }
            } else {
                Modal::alert([
                    trans('auth.message.find.failure', ['email' => $request->get('email')]),
                    trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
                ], 'error');

                return redirect(route('password.index'));

            }
        } catch (\Exception $e) {
            \Log::error($e);
            // notify the user & redirect
            Modal::alert([
                trans('auth.message.password_reset.failure'),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }
    }

}
