@extends('templates.back.full_layout')

@section('content')

    <div id="content" class="libraries files list">

        <div class="text-content">

            <div class="col-sm-12">

                {{-- Title--}}
                <h2>
                    <i class="fa fa-file" aria-hidden="true"></i>
                    {{ trans('libraries.files.page.title.management') }}
                </h2>

                <hr>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{ trans('libraries.files.page.title.upload') }}</h3>
                    </div>
                    <div class="panel-body">

                        <form id="dropzone" action="{{ route('libraries.files.store') }}" class="dropzone"
                              data-param="file"
                              data-accepted-extensions="{{ implode(',',config('libraries.files.accepted_extensions')) }}"
                              data-max-megabytes-size="{{ config('libraries.images.max-megabytes-size') }}">

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <h3 class="dz-message">
                                <i class="fa fa-cloud-upload" aria-hidden="true"></i> {{ trans('libraries.files.page.title.drop') }}
                            </h3>
                            
                            <div class="fallback hidden">
                                <input name="file" type="file" multiple />
                            </div>
                        </form>

                        {{-- global progress bar --}}
                        <span class="fileupload-process">
                            <div id="total-progress" class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                <div class="progress-bar progress-bar-success" data-dz-uploadprogress=""></div>
                            </div>
                        </span>

                        <div id="actions" class="text-right">
                            <p class="help-block quote pull-left">{!! config('settings.info_icon') !!} {{ trans('libraries.files.page.info.ext', ['ext' => implode(', ',config('libraries.files.accepted_extensions'))]) }}</p>
                            <button type="submit" class="btn btn-primary start" disabled="disabled">
                                <i class="fa fa-cloud-upload" aria-hidden="true"></i> {{ trans('libraries.files.page.action.start') }}
                            </button>
                            <button type="reset" class="btn btn-danger cancel">
                                <i class="fa fa-trash" aria-hidden="true"></i> {{ trans('libraries.files.page.action.remove') }}
                            </button>
                        </div>

                        <div id="previews"></div>

                    </div>
                </div>

                {{-- list --}}
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{ trans('libraries.files.page.title.list') }}</h3>
                    </div>

                    <div class="panel-body table-responsive">
                        @include('templates.back.partials.table-list')
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- HTML heavily inspired by http://blueimp.github.io/jQuery-File-Upload/ -->
    <div class="hidden">
        <div id="previews">
            <table id="template" class="table table-striped table-list">
                <tr>
                    <td class="file">
                        <i class="fa fa-file-o" aria-hidden="true"></i>
                    </td>
                    <td class="name">
                        <p class="name" data-dz-name></p>
                        <strong class="error text-danger" data-dz-errormessage></strong>
                    </td>
                    <td data-dz-size class="size"></td>
                    <td class="loading_status">
                        <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                            <div class="progress-bar progress-bar-success" data-dz-uploadprogress></div>
                        </div>
                    </td>
                    <td class="actions text-right">
                        <button class="btn btn-rounded btn-danger" data-dz-remove>
                            <i class="fa fa-ban" aria-hidden="true"></i>
                        </button>
                    </td>
                </tr>
            </table>
        </div>
    </div>

@endsection