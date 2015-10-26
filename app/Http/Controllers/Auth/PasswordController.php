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

    protected function index()
    {
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

            try{
                // we send the email with the reminder token
                $sent = \Mail::send('emails.password-reset', [
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

            } catch(\Exception $e) {
                dd($e->getMessage());
                \Log::error($e);
                // notify the user & redirect
                Modal::alert([
                    "Une erreur est survenue lors de l'envoi de l'e-mail. Veuillez contacter le support :" .
                    "<a href='mailto:" . config('app.email.support') . "' >" . config('app.email.support') . "</a>"
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
}
