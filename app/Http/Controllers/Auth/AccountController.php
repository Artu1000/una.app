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
    public function index()
    {
        // SEO Meta settings
        $this->seoMeta['page_title'] = 'Mon compte';

        // prepare data for the view
        $data = [
            'seoMeta' => $this->seoMeta,
            'user'    => \Sentinel::getUser(),
            'roles'   => \Sentinel::getRoleRepository()->all(),
        ];

        // return the view with data
        return view('pages.back.user-edit')->with($data);
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

            return Redirect()->back();
        }

        // we create the user
        if ($user = \Sentinel::register($request->all())) {

            // we create an activation line
            $activation = \Activation::create($user);

            try {
                // we send the email asking the account activation
                \Mail::send('emails.account-confirmation', [
                    'user'  => $user,
                    'token' => $activation->code,
                ], function ($email) use ($user) {
                    $email->from(config('mail.from.address'), config('mail.from.name'))
                        ->to($user->email, $user->first_name . ' ' . $user->last_name)
                        ->subject(config('mail.subject.prefix') . ' Réinitialisation de votre mot de passe.');
                });

                // notify the user & redirect
                \Modal::alert([
                    "Votre compte personnel a été créé. " .
                    "Un e-mail vous permettant d'activer votre compte vous a été envoyé. " .
                    "Vous le recevrez dans quelques instants.",
                ], 'success');

                return Redirect(route('login'));

            } catch (\Exception $e) {
                \Log::error($e);
                // notify the user & redirect
                \Modal::alert([
                    "Une erreur est survenue lors de l'envoi de votre e-mail d'activation. " .
                    "Veuillez contacter le support :" . "<a href='mailto:" . config('settings.support_email') . "' >" .
                    config('settings.support_email') . "</a>",
                ], 'error');

                return Redirect()->back();
            }
        }
    }

    /**
     * @return $this
     */
    public function createAccount()
    {
        // SEO Meta settings
        $this->seoMeta['page_title'] = 'Créer un compte';
        $this->seoMeta['meta_desc'] = 'Créez votre compte UNA et accédez à nos services en ligne.';
        $this->seoMeta['meta_keywords'] = 'club, universite, nantes, aviron, creer, creation, compte';

        // prepare data for the view
        $data = [
            'seoMeta' => $this->seoMeta,
            'css'     => url(elixir('css/app.login.css')),
        ];

        // return the view with data
        return view('pages.front.account-creation')->with($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function sendActivationMail(Request $request)
    {
        // we flash the email
        $request->flashOnly('email');

        if ($user = \Sentinel::findUserByCredentials($request->only('email'))
        ) {

            if (!$activation = \Activation::exists($user)) {
                $activation = \Activation::create($user);
            }

            try {
                // we send the email asking the account activation
                \Mail::send('emails.account-confirmation', [
                    'user'  => $user,
                    'token' => $activation->code,
                ], function ($email) use ($user) {
                    $email->from(config('mail.from.address'), config('mail.from.name'))
                        ->to($user->email, $user->first_name . ' ' . $user->last_name)
                        ->subject(config('mail.subject.prefix') . ' Réinitialisation de votre mot de passe.');
                });

                // notify the user & redirect
                \Modal::alert([
                    "Un e-mail vous permettant d'activer votre compte vous a été renvoyé. " .
                    "Vous le recevrez dans quelques instants.",
                ], 'success');

                return Redirect(route('login'));

            } catch (\Exception $e) {
                \Log::error($e);
                // notify the user & redirect
                \Modal::alert([
                    "Une erreur est survenue lors de l'envoi de votre e-mail d'activation. " .
                    "Veuillez contacter le support :" . "<a href='mailto:" . config('settings.support_email') . "' >" .
                    config('settings.support_email') . "</a>",
                ], 'error');

                return Redirect()->back();
            }

        } else {
            \Modal::alert([
                "Aucun utilisateur correspondant à l'adresse e-mail <b>" . $request->get('email') .
                "</b> n'a été trouvé.",
            ], 'error');

            return Redirect()->back();
        }
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function activateAccount(Request $request)
    {
        // we try to find the user from its email
        if ($user = \Sentinel::findByCredentials($request->only('email'))) {

            // we verify if the reminder token is valid
            if (\Activation::complete($user, $request->get('token'))) {
                \Modal::alert([
                    "Félicitations " . $user->first_name . " " . $user->last_name .
                    ", votre compte est maintenant activé. Vous pouvez maintenant vous y connecter.",
                ], 'success');

                return Redirect(route('login'))->withInput($request->only('email'));
            } else {
                \Modal::alert([
                    "La clé d'activation de votre compte est incorrecte ou a expirée. ",
                    "Pour recevoir une nouvelle clé d'activation, <a href='" . route('send_activation_mail', [
                        'email' => $request->get('email'),
                    ]) . "' title=\"Me renvoyer l'email d'activation\"><u>cliquez ici</u></a>.",
                ], 'error');

                return Redirect(route('login'))->withInput($request->only('email'));
            }
        } else {
            // notify the user & redirect
            \Modal::alert([
                "Aucun utilisateur correspondant à l'adresse e-mail <b>" . $request->get('email') .
                "</b> n'a été trouvé.",
            ], 'error');

            return Redirect(route('login'))->withInput($request->only('email'));
        }
    }

}
