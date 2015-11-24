<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
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
            'user' => \Sentinel::getUser(),
            'roles' => \Sentinel::getRoleRepository()->all()
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
            'last_name' => 'required',
            'first_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed'
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
                    'user' => $user,
                    'token' => $activation->code
                ], function ($email) use ($user) {
                    $email->from(config('mail.from.address'), config('mail.from.name'))
                        ->to($user->email, $user->first_name . ' ' . $user->last_name)
                        ->subject(config('mail.subject.prefix') . ' Réinitialisation de votre mot de passe.');
                });

                // notify the user & redirect
                \Modal::alert([
                    "Votre compte personnel a été créé. " .
                    "Un e-mail vous permettant d'activer votre compte vous a été envoyé. " .
                    "Vous le recevrez dans quelques instants."
                ], 'success');
                return Redirect(route('login'));

            } catch (\Exception $e) {
                \Log::error($e);
                // notify the user & redirect
                \Modal::alert([
                    "Une erreur est survenue lors de l'envoi de votre e-mail d'activation. " .
                    "Veuillez contacter le support :" . "<a href='mailto:" . config('settings.support_email') . "' >" .
                    config('settings.support_email') . "</a>"
                ], 'error');
                return Redirect()->back();
            }
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // we flash the non sensitive data
        $request->flashOnly(
            'gender',
            'last_name',
            'first_name',
            'birth_date',
            'phone_number',
            'email',
            'address',
            'zip_code',
            'city',
            'country'
        );

        $inputs = $request->all();

        // we convert the french formatted date to its english format
        $inputs['birth_date'] = null;
        if (!empty($birth_date = $request->get('birth_date'))) {
            $inputs['birth_date'] = Carbon::createFromFormat('d/m/Y', $birth_date)->format('Y-m-d');
        }

        // we check the inputs
        $errors = [];
        $validator = \Validator::make($inputs, [
            'photo' => 'mimes:jpg,jpeg,png',
            'gender' => 'in:' . implode(',', array_keys(config('user.gender'))),
            'last_name' => 'required',
            'first_name' => 'required',
            'birth_date' => 'date_format:Y-m-d',
            'phone_number' => 'phone:FR',
            'email' => 'required|email|unique:users,email,' . $request->get('_id'),
            'zip_code' => 'digits:5',
            'password' => 'min:6|confirmed',
        ]);
        foreach ($validator->errors()->all() as $error) {
            $errors[] = $error;
        }
        // if errors are found
        if (count($errors)) {
            \Modal::alert($errors, 'error');
            return Redirect()->back();
        }

        // we format the number into its international equivalent
        if(!empty($inputs['phone_number'])){
            try{
                $inputs['phone_number'] = $formatted_phone_number = phone_format(
                    $inputs['phone_number'],
                    'FR',
                    \libphonenumber\PhoneNumberFormat::INTERNATIONAL
                );
            } catch(\Exception $e){
                \Modal::alert([
                    "Une erreur est survenue lors du traitement du numéro de téléphone." .
                    "Veuillez contacter le support :" . "<a href='mailto:" . config('settings.support_email') . "' >" .
                    config('settings.support_email') . "</a>"
                ], 'error');
                return Redirect()->back();
            }
        }

        // we store the photo
        if ($photo = $request->file('photo')) {
            try {
                // we resize, optimize and save the image
                $file_name =\ImageManager::resize(
                    $photo,
                    \Sentinel::getUser()->id . '_photo', 'user',
                    \Sentinel::getUser()->image_sizes
                );
                // we add the image name to the inputs for saving
                $inputs['photo'] = $file_name;
            } catch (\Exception $e) {
                \Modal::alert([
                    "Une erreur est survenue lors de l'enregistrement de la photo. " .
                    "Veuillez contacter le support :" . "<a href='mailto:" . config('settings.support_email') . "' >" .
                    config('settings.support_email') . "</a>"
                ], 'error');
                return Redirect()->back();
            }
        }

        // we update the user
        if (\Sentinel::update(\Sentinel::getUser(), $inputs)) {

            \Modal::alert([
                "Vos données personnelles ont bien été mises à jour."
            ], 'success');
            return Redirect()->back();
        }

        \Modal::alert([
            "Une erreur s'est déroulée lors de la mise à jour de vos données personnelles."
        ], 'error');
        return Redirect()->back();
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
            'css' => url(elixir('css/app.login.css'))
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
                    'user' => $user,
                    'token' => $activation->code
                ], function ($email) use ($user) {
                    $email->from(config('mail.from.address'), config('mail.from.name'))
                        ->to($user->email, $user->first_name . ' ' . $user->last_name)
                        ->subject(config('mail.subject.prefix') . ' Réinitialisation de votre mot de passe.');
                });

                // notify the user & redirect
                \Modal::alert([
                    "Un e-mail vous permettant d'activer votre compte vous a été renvoyé. " .
                    "Vous le recevrez dans quelques instants."
                ], 'success');
                return Redirect(route('login'));

            } catch (\Exception $e) {
                \Log::error($e);
                // notify the user & redirect
                \Modal::alert([
                    "Une erreur est survenue lors de l'envoi de votre e-mail d'activation. " .
                    "Veuillez contacter le support :" . "<a href='mailto:" . config('settings.support_email') . "' >" .
                    config('settings.support_email') . "</a>"
                ], 'error');
                return Redirect()->back();
            }

        } else {
            \Modal::alert([
                "Aucun utilisateur correspondant à l'adresse e-mail <b>" . $request->get('email') .
                "</b> n'a été trouvé."
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
                    ", votre compte est maintenant activé. Vous pouvez maintenant vous y connecter."
                ], 'success');
                return Redirect(route('login'))->withInput($request->only('email'));
            } else {
                \Modal::alert([
                    "La clé d'activation de votre compte est incorrecte ou a expirée. ",
                    "Pour recevoir une nouvelle clé d'activation, <a href='" . route('send_activation_mail', [
                        'email' => $request->get('email')
                    ]) . "' title=\"Me renvoyer l'email d'activation\"><u>cliquez ici</u></a>."
                ], 'error');
                return Redirect(route('login'))->withInput($request->only('email'));
            }
        } else {
            // notify the user & redirect
            \Modal::alert([
                "Aucun utilisateur correspondant à l'adresse e-mail <b>" . $request->get('email') .
                "</b> n'a été trouvé."
            ], 'error');
            return Redirect(route('login'))->withInput($request->only('email'));
        }
    }

}
