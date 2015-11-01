@extends('templates.back.full_layout')

@section('content')

    <div id="content" class="account row">

        <div class="text-content">

            <div class="col-sm-12">

                <form role="form" method="POST" action="{{ route('account') }}">

                    {{-- crsf token --}}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    {{-- Title--}}
                    <h2><i class="fa fa-user"></i> Mon profil</h2>

                    <hr>

                    {{-- lastname input --}}
                    <label class="sr-only" for="input_lastname">NOM</label>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon" for="input_lastname"><i class="fa fa-user"></i></span>
                            <input id="input_lastname" class="form-control capitalize" type="text" name="last_name" value="{{ old('last_name') }}" placeholder="NOM" autofocus>
                        </div>
                    </div>

                    {{-- firstname input --}}
                    <label class="sr-only" for="input_firstname">Prénom</label>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon" for="input_firstname"><i class="fa fa-user"></i></span>
                            <input id="input_firstname" class="form-control capitalize-first-letter" type="text" name="first_name" value="{{ old('first_name') }}" placeholder="Prénom">
                        </div>
                    </div>

                    {{-- email input --}}
                    <label class="sr-only" for="input_email">Adresse e-mail</label>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon" for="input_email"><i class="fa fa-at"></i></span>
                            <input id="input_email" class="form-control" type="email" name="email" value="{{ old('email') }}" placeholder="Adresse e-mail" autofocus>
                        </div>
                    </div>

                    {{-- password input--}}
                    <label for="input_password" class="sr-only">Password</label>
                    <div class="form-group">
                        <div class="input-group">
                                <span class="input-group-addon" for="input_password">
                                    <i class="fa fa-unlock-alt"></i>
                                </span>
                            <input type="password" id="input_password" class="form-control" name="password" placeholder="Mot de passe" title="Mot de passe">
                        </div>
                    </div>

                    {{-- password confirmation input --}}
                    <label class="sr-only" for="input_password_confirmation">Confirmation du mot de passe</label>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon" for="input_password_confirmation"><i class="fa fa-unlock-alt"></i></span>
                            <input id="input_password_confirmation" class="form-control" type="password" name="password_confirmation" placeholder="Confirmation du mot de passe">
                        </div>
                    </div>

                    {{-- submit login --}}
                    <button class="btn btn-lg btn-primary btn-block" type="submit">
                        <i class="fa fa-floppy-o"></i> Enregistrer les modifications
                    </button>
                </form>

            </div>
        </div>
    </div>

@endsection