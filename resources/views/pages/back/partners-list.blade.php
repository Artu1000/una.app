@extends('templates.back.full_layout')

@section('content')

    <div id="content" class="partners">

        <div class="text-content">

            <div class="col-sm-12">

                {{-- Title--}}
                <h2><i class="fa fa-users"></i> {{ trans('partners.page.title.management') }}</h2>

                <hr>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{ trans('partners.page.title.list') }}</h3>
                    </div>
                    <div class="panel-body table-responsive">

                        @include('templates.back.partials.table-list')

                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection