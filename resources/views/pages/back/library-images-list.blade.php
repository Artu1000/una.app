@extends('templates.back.full_layout')

@section('content')

    <div id="content" class="libraries images list">

        <div class="text-content">

            <div class="col-sm-12">

                {{-- Title--}}
                <h2>
                    <i class="fa fa-file-image-o" aria-hidden="true"></i>
                    {{ trans('libraries.images.page.title.management') }}
                </h2>

                <hr>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{ trans('libraries.images.page.title.upload') }}</h3>
                    </div>
                    <div class="panel-body">

                        <form id="dropzone" action="{{ route('libraries.images.store') }}" class="dropzone"
                              data-param="image"
                              data-accepted-extensions="{{ implode(',',config('libraries.images.accepted_extensions')) }}"
                              data-max-megabytes-size="{{ config('libraries.files.max-megabytes-size') }}">

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <h3 class="dz-message">
                                <i class="fa fa-cloud-upload" aria-hidden="true"></i> {{ trans('libraries.images.page.title.drop') }}
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
                            <p class="help-block quote pull-left">{!! config('settings.info_icon') !!} {{ trans('libraries.images.page.info.ext', ['ext' => implode(', ',config('libraries.images.accepted_extensions'))]) }}</p>
                            <button type="submit" class="btn btn-primary start" disabled="disabled">
                                <i class="fa fa-cloud-upload" aria-hidden="true"></i> {{ trans('libraries.images.page.action.start') }}
                            </button>
                            <button type="reset" class="btn btn-danger cancel">
                                <i class="fa fa-trash" aria-hidden="true"></i> {{ trans('libraries.images.page.action.remove') }}
                            </button>
                        </div>

                        <div id="previews"></div>

                    </div>
                </div>

                {{-- list --}}
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{ trans('libraries.images.page.title.list') }}</h3>
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
                    <td class="image">
                        <img data-dz-thumbnail class="img-thumbnail">
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