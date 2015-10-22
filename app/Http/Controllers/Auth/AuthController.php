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
        $this->seoMeta['page_title'] = 'Espace connexion';
        $this->seoMeta['meta_desc'] = 'Connectez-vous à votre espace personnel afin de profiter des différentes
        fonctionnalités de l\'application';
        $this->seoMeta['meta_keywords'] = 'club, universite, nantes, aviron, espace, connexion';

        // data send to the view
        $data = [
            'seoMeta' => $this->seoMeta,
            'css' => elixir('css/app.login.css')
        ];
        return view('pages.front.login')->with($data);
    }

    protected function store(Request $request)
    {
        // we flash inputs
        $request->flashOnly('email', 'password', 'remember');

        // we analyse the given inputs
        // we replace the "on" or "off" value from the checkbox by a boolean
        $request->merge([
            'remember' => filter_var($request->get('remember'), FILTER_VALIDATE_BOOLEAN)
        ]);
        $errors = [];
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'remember' => 'boolean',
        ]);
        foreach ($validator->errors()->all() as $error) {
            $errors[] = $error;
        }
        // if errors are found
        if (count($errors)) {
            Modal::alert($errors, 'error');
            return Redirect()->back();
        }

        // we try to authenticate the user
        try {
            $user = Sentinel::authenticate($request->except('remember'), $request->get('remember'));

            if(!$user){
                Modal::alert([
                    "L'e-mail ou le mot de passe est erroné. Veuillez rééssayer."
                ], 'error');
            }

            // redirect to the url stored in the session
            if($url = \Session::get('previous_url')){
                return redirect($url);
            } else {
                // or redirect to home
                return redirect(route('home'));
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            Modal::alert([
                $e->getMessage()
            ], 'error');
            return Redirect()->back();
        }
    }
}
