@extends('templates.back.full_layout')

@section('content')

    <div id="content" class="account row">

        <div class="text-content">

            <div class="col-sm-12">

                {{-- Title--}}
                <h2><i class="fa fa-user"></i> Mon profil</h2>

                <hr>

                <form role="form" method="POST" action="{{ route('update_account') }}" enctype="multipart/form-data">

                    {{-- crsf token --}}
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_id" value="{{ $user->id }}">

                    {{-- personal data --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Données personnelles</h3>
                        </div>
                        <div class="panel-body">

                            {{-- photo --}}
                            <div class="form-group">
                                <img width="145" height="160" src="{{ route('image', ['filename' => $user->photo, 'folder' => 'user', 'size' => $user->size('picture')]) }}" alt="{{ $user->first_name }} {{ $user->last_name }}">
                            </div>

                            <div class="form-group">
                                <p class="quote text-info"><i class="fa fa-info-circle"></i> Taille : 145 x 160 (px) - Formats acceptés : jpg, jpeg, png</p>
                                <div class="">
                                    <div class="input-group inline-block">
                                        <span class="input-group-btn">
                                            <span class="btn btn-primary btn-file">
                                                Parcourir <input type="file" name="photo">
                                            </span>
                                        </span>
                                        <input type="text" class="form-control" readonly="">
                                    </div>
                                </div>
                            </div>

                            {{-- gender --}}
                            <div class="form-group">
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-primary
                                    @if(old('gender') == config('user.gender_key.female.id')) active
                                    @elseif($user->gender == config('user.gender_key.female.id')) active
                                    @endif">
                                        <input type="radio" name="gender" value="{{ config('user.gender_key.female.id') }}" id="option1" autocomplete="off"
                                                @if(old('gender') == config('user.gender_key.female.id')) checked
                                                @elseif($user->gender == config('user.gender_key.female.id')) checked
                                                @endif>
                                        <i class="fa fa-female"></i>
                                        {{ config('user.gender_key.female.title') }}
                                    </label>
                                    <label class="btn btn-primary
                                    @if(old('gender') == config('user.gender_key.male.id')) active
                                    @elseif($user->gender == config('user.gender_key.male.id')) active
                                    @endif">
                                        <input type="radio" name="gender" value="{{ config('user.gender_key.male.id') }}" id="option2" autocomplete="off"
                                                @if(old('gender') == config('user.gender_key.male.id')) checked
                                                @elseif($user->gender == config('user.gender_key.male.id')) checked
                                                @endif>
                                        <i class="fa fa-male"></i>
                                        {{ config('user.gender_key.male.title') }}
                                    </label>
                                </div>
                            </div>

                            {{-- lastname --}}
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_lastname"><i class="fa fa-user"></i></span>
                                    <input id="input_lastname" class="form-control capitalize" type="text" name="last_name" value="{{ old('last_name') ? old('last_name') : $user->last_name }}" placeholder="NOM">
                                </div>
                            </div>

                            {{-- firstname --}}
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_firstname"><i class="fa fa-user"></i></span>
                                    <input id="input_firstname" class="form-control capitalize-first-letter" type="text" name="first_name" value="{{ old('first_name') ? old('first_name') : $user->first_name }}" placeholder="Prénom">
                                </div>
                            </div>

                            {{-- birth date --}}
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_birth_date"><i class="fa fa-birthday-cake"></i></span>
                                    <input id="input_birth_date" type='text' class="form-control datepicker" name="birth_date" value="{{ old('birth_date') ? old('birth_date') : $user->birth_date }}" placeholder="Date de naissance (jj/mm/aaaa)">
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- contact data --}}
                    <div class="panel panel-default panel-responsive">
                        <div class="panel-heading">
                            <h3 class="panel-title">Contact</h3>
                        </div>
                        <div class="panel-body">

                            {{-- phone number --}}
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_phone_number"><i class="fa fa-phone"></i></span>
                                    <input id="input_phone_number" class="form-control" type="tel" name="phone_number" value="{{ old('phone_number') ? old('phone_number') : $user->phone_number }}" placeholder="Numéro de téléphone">
                                </div>
                            </div>

                            {{-- email --}}
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_email"><i class="fa fa-at"></i></span>
                                    <input id="input_email" class="form-control" type="email" name="email" value="{{ old('email') ? old('email') : $user->email }}" placeholder="Adresse e-mail">
                                </div>
                            </div>

                            {{-- address --}}
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_address"><i class="fa fa-envelope"></i></span>
                                    <input id="input_address" class="form-control" type="text" name="address" value="{{ old('address') ? old('address') : $user->address }}" placeholder="Adresse postale">
                                </div>
                            </div>

                            {{-- zip code --}}
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_zip_code"><i class="fa fa-paper-plane"></i></span>
                                    <input id="input_zip_code" class="form-control" type="number" name="zip_code" value="{{ old('zip_code') ? old('zip_code') : $user->zip_code }}" placeholder="Code postal">
                                </div>
                            </div>

                            {{-- city --}}
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_city"><i class="fa fa-map-marker"></i></span>
                                    <input id="input_city" class="form-control" type="text" name="city" value="{{ old('city') ? old('city') : $user->city }}" placeholder="Ville">
                                </div>
                            </div>

                            {{-- country --}}
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_country"><i class="fa fa-globe"></i></span>
                                    <input id="input_country" class="form-control" type="text" name="country" value="{{ old('country') ? old('country') : $user->country }}" placeholder="Pays">
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- security data --}}
                    <div class="panel panel-default panel-responsive">
                        <div class="panel-heading">
                            <h3 class="panel-title">Sécurité</h3>
                        </div>
                        <div class="panel-body">

                            <p class="quote text-info"><i class="fa fa-info-circle"></i> Ne remplir que si vous souhaitez modifier votre mot de passe actuel.</p>

                            {{-- password input--}}
                            <div class="form-group">
                                <div class="input-group">
                                        <span class="input-group-addon" for="input_password">
                                            <i class="fa fa-unlock-alt"></i>
                                        </span>
                                    <input type="password" id="input_password" class="form-control" name="password" placeholder="Mot de passe" title="Mot de passe">
                                </div>
                            </div>

                            {{-- password confirmation input --}}
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" for="input_password_confirmation"><i class="fa fa-unlock-alt"></i></span>
                                    <input id="input_password_confirmation" class="form-control" type="password" name="password_confirmation" placeholder="Confirmation du mot de passe">
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- submit login --}}
                    <button class="btn btn-lg btn-primary spin-on-click" type="submit">
                        <i class="fa fa-floppy-o"></i> Enregistrer les modifications
                    </button>
                </form>

            </div>
        </div>
    </div>

@endsection