<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use CustomLog;
use Exception;
use Illuminate\Http\Request;
use InputSanitizer;
use Modal;
use Sentinel;
use Validation;

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
        $this->seo_meta['page_title'] = trans('seo.front.login.index.title');
        $this->seo_meta['meta_desc'] = trans('seo.front.login.index.description');
        $this->seo_meta['meta_keywords'] = trans('seo.front.login.index.keywords');

        // data send to the view
        $data = [
            'seo_meta' => $this->seo_meta,
            'css'      => elixir('css/app.auth.css'),
        ];

        return view('pages.front.login')->with($data);
    }

    protected function login(Request $request)
    {
        // we flash the request
        $request->flash();

        // we sanitize the entries
        $request->replace(InputSanitizer::sanitize($request->all()));

        // we set the remember to false if we do not find it
        $request->merge(['remember' => $request->get('remember', false)]);

        // we check the inputs validity
        $rules = [
            'email'    => 'required|email',
            'password' => 'required',
            'remember' => 'required|boolean',
        ];
        if (!Validation::check($request->all(), $rules)) {

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

            // we notify the current user
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
        } catch (NotActivatedException $e) {

            // we log the error
            CustomLog::error($e);

            // we notify the current user
            Modal::alert([
                trans('auth.message.activation.failure'),
                trans('auth.message.activation.email.resend', [
                    'email' => $request->get('email'),
                    'url'   => route('account.activation_email', ['email' => $request->get('email')]),
                ]),
            ], 'error');

            return redirect()->back();
        } catch (ThrottlingException $e) {

            switch ($e->getType()) {
                case 'ip':
                    // we notify the current user
                    Modal::alert([
                        trans('auth.message.throttle.ip', ['seconds' => $e->getDelay()]),
                    ], 'error');
                    break;
                default:
                    // we notify the current user
                    Modal::alert([
                        $e->getMessage(),
                    ], 'error');
                    break;
            }

            return redirect()->back();
        } catch (Exception $e) {

            // we notify the current user
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
        } catch (Exception $e) {

            // we log the error
            CustomLog::error($e);

            // we notify the current user
            Modal::alert([
                trans('auth.message.logout.failure', ['name' => $user->first_name . ' ' . $user->last_name]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }
    }
}
