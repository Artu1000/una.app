<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modal;
use Sentinel;
use Validator;

class AuthController extends Controller
{

    protected $redirectPath;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->redirectPath = url('home');
    }

    /**
     * login
     *
     * @return $this
     */
    protected function index()
    {
        // SEO settings
        $this->seoMeta['page_title'] = trans('seo.login.index.title');
        $this->seoMeta['meta_desc'] = trans('seo.login.index.description');
        $this->seoMeta['meta_keywords'] = trans('seo.login.index.keywords');

        // data send to the view
        $data = [
            'seoMeta' => $this->seoMeta,
            'css'     => url(elixir('css/app.login.css')),
        ];

        return view('pages.front.login')->with($data);
    }

    protected function login(Request $request)
    {
        // we flash inputs
        $request->flash();

        // we replace the "on" or "off" value from the checkbox by a boolean
        $request->merge([
            'remember' => filter_var($request->get('remember'), FILTER_VALIDATE_BOOLEAN),
        ]);

        // we analyse the given inputs
        $errors = [];
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
            'remember' => 'required|boolean',
        ]);
        foreach ($validator->errors()->all() as $error) {
            $errors[] = $error;
        }
        // if errors are found
        if (count($errors)) {
            Modal::alert($errors, 'error');

            return redirect()->back();
        }

        // we try to authenticate the user
        try {
            if (!$user = Sentinel::authenticate($request->except('remember'), $request->get('remember'))) {
                Modal::alert([
                    trans('auth.message.login.failure'),
                ], 'error');

                return redirect()->back();
            }

            Modal::alert([
                trans('auth.message.login.success', ['name' => $user->first_name . " " . $user->last_name]),
            ], 'success');

            // redirect to the url stored in the session
            if ($url = session()->get('previous_url')) {
                session()->forget('previous_url');

                return redirect($url);
            } else {
                // or redirect to home
                return redirect(route('home'));
            }
        } catch (\Cartalyst\Sentinel\Checkpoints\NotActivatedException $e) {
            \Log::error($e);
            Modal::alert([
                trans('auth.message.activation.failure'),
                trans('auth.message.activation.email.resend', [
                    'email' => $request->get('email'),
                    'url'   => route('account.activation_email', ['email' => $request->get('email')]),
                ]),
            ], 'error');

            return redirect()->back();
        } catch (\Cartalyst\Sentinel\Checkpoints\ThrottlingException $e) {
            switch ($e->getType()) {
                case 'ip':
                    Modal::alert([
                        trans('auth.message.throttle.ip', ['seconds' => $e->getDelay()]),
                    ], 'error');
                    break;
                default:
                    Modal::alert([
                        $e->getMessage(),
                    ], 'error');
                    break;
            }

            return redirect()->back();
        } catch (\Exception $e) {
            \Modal::alert([
                trans('auth.message.login.error'),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }
    }

    public function logout()
    {
        // we get the current user
        $user = \Sentinel::getUser();

        // we logout the user
        try {
            Sentinel::logout();
            Modal::alert([
                trans('auth.message.logout.success', ['name' => $user->first_name . ' ' . $user->last_name]),
            ], 'success');

            return redirect(route('home'));
        } catch (\Exception $e) {
            Modal::alert([
                trans('auth.message.logout.failure', ['name' => $user->first_name . ' ' . $user->last_name]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }
    }
}
