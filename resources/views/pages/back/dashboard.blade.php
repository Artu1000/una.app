@extends('templates.back.full_layout')

@section('content')

    <div id="content" class="dashboard">

        <div class="text-content">

            <div class="col-sm-12">

                <p>Bienvenue dans votre interface d'administration.</p>

                <div class="col-lg-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 style="color:#fff;" class="panel-title">{{ trans('dashboard.registration_form_downloads.title') }}</h3>
                        </div>
                        <div class="panel-body">
                            <ul class="list-group">

                                @foreach($registration_form_downloads as $key => $registration_form_download)
                                    <li class="list-group-item {{ $registration_form_download['count'] > 0 ? 'list-group-item-success' : 'list-group-item-danger' }}">
                                        <span class="badge">{{ $registration_form_download['count'] }}</span>
                                        {{ trans('dashboard.qr_code_scans.' . $key) }} ({{ $registration_form_download['start_date'] }} => {{ $registration_form_download['end_date'] }})
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 style="color:#fff;" class="panel-title">{{ trans('dashboard.qr_code_scans.title') }}</h3>
                        </div>
                        <div class="panel-body">
                            <ul class="list-group">

                                @foreach($qr_code_scans as $key => $qr_code_scan)
                                    <li class="list-group-item {{ $qr_code_scan['count'] > 0 ? 'list-group-item-success' : 'list-group-item-danger' }}">
                                        <span class="badge">{{ $qr_code_scan['count'] }}</span>
                                        {{ trans('dashboard.qr_code_scans.' . $key) }} ({{ $qr_code_scan['start_date'] }} => {{ $qr_code_scan['end_date'] }})
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

@endsection