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
        $this->seoMeta['page_title'] = 'Mot de passe oublié';
        $this->seoMeta['meta_desc'] = 'Suivez les instructions afin de réinitialiser votre mot de passe.';
        $this->seoMeta['meta_keywords'] = 'club, universite, nantes, aviron, espace, mot, de, passe, oublie';

        // data send to the view
        $data = [
            'seoMeta' => $this->seoMeta,
            'email' => $request->get('email'),
            'css' => elixir('css/app.login.css')
        ];
        return view('pages.front.forgotten-password')->with($data);
    }

    protected function store(Request $request)
    {
        // we flash inputs
        $request->flashOnly('email');

        // we check the inputs
        $errors = [];
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);
        foreach ($validator->errors()->all() as $error) {
            $errors[] = $error;
        }
        if (count($errors)) {
            // we notify the user
            Modal::alert($errors, 'error');
            return Redirect()->back();
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
                    'user' => $user,
                    'token' => $reminder->code
                ], function ($email) use ($user) {
                    $email->from(config('mail.from.address'), config('mail.from.name'))
                        ->to($user->email, $user->first_name . ' ' . $user->last_name)
                        ->subject(config('mail.subject.prefix') . ' Réinitialisation de votre mot de passe.');
                });

                // notify the user & redirect
                Modal::alert([
                    "Un e-mail contenant les instructions de réinitialisation de votre mot de passe vous a été envoyé."
                ], 'success');
                return Redirect(route('login'));

            } catch (\Exception $e) {
                dd($e->getMessage());
                \Log::error($e);
                // notify the user & redirect
                Modal::alert([
                    "Une erreur est survenue lors de l'envoi de votre e-mail de réinitialisation. " .
                    "Veuillez contacter le support :" . "<a href='mailto:" . config('settings.support_email') . "' >" .
                    config('settings.support_email') . "</a>"
                ], 'error');
                return Redirect()->back();
            }
        } else {
            // notify the user & redirect
            Modal::alert([
                "Aucun utilisateur correspondant à l'adresse e-mail <b>" . $request->get('email') .
                "</b> n'a été trouvé."
            ], 'error');
            return Redirect()->back();
        }
    }

    public function show($email, Request $request)
    {
        // we try to find the user from its email
        if ($user = Sentinel::findByCredentials(['email' => $email])) {
            // we verify if the reminder token is valid
            if (\Reminder::exists($user, $request->only('token'))) {
                // SEO settings
                $this->seoMeta['page_title'] = 'Réinitialisation du mot de passe';
                $this->seoMeta['meta_desc'] = 'Réinitialisez votre mot de passe de manière sécurisée.';

                // data send to the view
                $data = [
                    'email' => $email,
                    'reminder' => $request->get('token'),
                    'seoMeta' => $this->seoMeta,
                    'css' => elixir('css/app.login.css')
                ];
                return view('pages.front.password-recovery')->with($data);
            } else {
                // notify the user & redirect
                Modal::alert([
                    "La clé de réinitialisation du mot de passe est incorrecte ou a expirée. " .
                    "Merci de renouveler l'opération."
                ], 'error');
                return Redirect(route('forgotten_password'));
            }
        } else {
            // notify the user & redirect
            Modal::alert([
                "Aucun utilisateur correspondant à l'adresse e-mail <b>" . $request->get('email') .
                "</b> n'a été trouvé."
            ], 'error');
            return Redirect(route('forgotten_password'));
        }
    }

    public function update(Request $request)
    {
        // we check the inputs
        $errors = [];
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6|confirmed'
        ]);
        foreach ($validator->errors()->all() as $error) {
            $errors[] = $error;
        }
        if (count($errors)) {
            // we notify the user
            Modal::alert($errors, 'error');
            return Redirect()->back();
        }

        // we try to find the user from its email
        if ($user = Sentinel::findByCredentials(['email' => $request->only('_email')])) {
            // we try to complete the password reset
            if ($reminder = \Reminder::complete($user, $request->only('_reminder'), $request->get('password'))) {
                Modal::alert([
                    "Votre nouveau mot de passe a bien été enregistré. " .
                    "Connectez-vous dès à présent sur votre espace personnel."
                ], 'success');
                return Redirect(route('login'));
            } else {
                Modal::alert([
                    "La clé de réinitialisation du mot de passe est incorrecte ou a expirée. " .
                    "Merci de renouveler l'opération."
                ], 'error');
                return Redirect(route('forgotten_password'));
            }
        } else {
            Modal::alert([
                "Aucun utilisateur correspondant à l'adresse e-mail <b>" . $request->get('email') .
                "</b> n'a été trouvé."
            ], 'error');
            return Redirect(route('forgotten_password'));

        }
    }

}
