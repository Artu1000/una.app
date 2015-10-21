<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Modal;
use Sentinel;
use Validator;

class AuthController extends Controller
{

    protected $redirectPath;
//    use AuthenticatesAndRegistersUsers;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->redirectPath = url('/');
        $this->middleware('guest', ['except' => 'getLogout']);
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

        // we replace the "on" or "off" value from the checkbox by a boolean
        $request->merge([
            'remember' => filter_var($request, FILTER_VALIDATE_BOOLEAN)
        ]);

        // we analyse the given inputs
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
            $status = Sentinel::authenticate($request->all());
            if($url = \Session::get('previous_url')){
                return redirect($url);
            } else {
                return redirect('home');
            }
        } catch (\Exception $e) {
            Modal::alert([
                $e->getMessage()
            ], 'error');
            return Redirect()->back();
        }
    }
}
