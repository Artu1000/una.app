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
        $request->flashOnly('email', 'remember');

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
            if (!$user = Sentinel::authenticate($request->except('remember'), $request->get('remember'))) {
                Modal::alert([
                    "E-mail ou mot de passe erroné. Veuillez rééssayer."
                ], 'error');
                return Redirect()->back();
            }

            Modal::alert([
                "Bienvenue " . $user->first_name . " " . $user->last_name . ", vous êtes maintenant connecté."
            ], 'success');

            // redirect to the url stored in the session
            if ($url = \Session::get('previous_url')) {
                return redirect($url);
            } else {
                // or redirect to home
                return redirect(route('home'));
            }
        } catch (\Cartalyst\Sentinel\Checkpoints\NotActivatedException $e) {
            \Log::error($e);
            Modal::alert([
                "Votre compte n'est pas activé. " .
                "Activez-le à partir du lien qui vous a été transmis par e-mail lors de votre inscription. ",
                "Pour recevoir à nouveau votre e-mail d'activation, <a href='" . route('send_activation_mail', [
                    'email' => $request->get('email')
                ]) . "' title=\"Me renvoyer l'email d'activation\"><u>cliquez ici</u></a>."
            ], 'error');
            return Redirect()->back();
        } catch (\Cartalyst\Sentinel\Checkpoints\ThrottlingException $e) {
            switch ($e->getType()) {
                case 'ip':
                    Modal::alert([
                        "En raison d'erreurs répétées, l'accès à l'application depuis votre IP est suspendu pendant " .
                        $e->getDelay() . " secondes."
                    ], 'error');
                    break;
                default:
                    Modal::alert([
                        $e->getMessage()
                    ], 'error');
                    break;
            }
            return Redirect()->back();
        }
    }
}
