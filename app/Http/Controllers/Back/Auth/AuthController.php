<?php

namespace App\Http\Controllers\Back\Auth;

//use App\Models\User;
//use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{

    use AuthenticatesAndRegistersUsers;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
//    public function __construct()
//    {
//        $this->middleware('guest', ['except' => 'getLogout']);
//    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
//    protected function validator(array $data)
//    {
//        return Validator::make($data, [
//            'name' => 'required|max:255',
//            'email' => 'required|email|max:255|unique:users',
//            'password' => 'required|confirmed|min:6',
//        ]);
//    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
//    protected function create(array $data)
//    {
//        return User::create([
//            'name' => $data['name'],
//            'email' => $data['email'],
//            'password' => bcrypt($data['password']),
//        ]);
//    }

    /**
     * login
     *
     * @return $this
     */
    protected function show(){

        // SEO settings
        $this->seoMeta['page_title'] = 'Espace connexion';
        $this->seoMeta['meta_desc'] = 'Connectez-vous à votre espace personnel afin de profiter des différentes
        fonctionnalités de l\'application';
        $this->seoMeta['meta_keywords'] = 'club, universite, nantes, aviron, espace, connexion';

        // data send to the view
        $data = [
            'seoMeta' => $this->seoMeta
        ];
        return view('pages.back.auth.login')->with($data);

    }
}
