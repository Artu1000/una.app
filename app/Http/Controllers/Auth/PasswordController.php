<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modal;
use Sentinel;
use Validator;

class PasswordController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function index(){
        // SEO settings
        $this->seoMeta['page_title'] = 'Mot de passe oublié';
        $this->seoMeta['meta_desc'] = 'Suivez les instructions afin de réinitialiser votre mot de passe.';
        $this->seoMeta['meta_keywords'] = 'club, universite, nantes, aviron, espace, mot, de, passe, oublie';

        // data send to the view
        $data = [
            'seoMeta' => $this->seoMeta,
            'css' => elixir('css/app.login.css')
        ];
        return view('pages.front.forgotten-password')->with($data);
    }

    protected function store(Request $request)
    {
        // we flash inputs
        $request->flashOnly('email');

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ]);
        foreach ($validator->errors()->all() as $error) {
            $errors[] = $error;
        }
        // if errors are found
        if (count($errors)) {
            Modal::alert($errors, 'error');
            return Redirect()->back();
        }
    }
}
