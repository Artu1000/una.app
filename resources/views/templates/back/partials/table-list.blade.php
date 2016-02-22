<table class="table table-striped table-hover table-list">

    <thead>

    {{-- table head --}}
    @if(
        $tableListData['enable_lines_choice']
        || !empty($tableListData['search_config'])
    )
        <tr>

            <td colspan="{{ sizeof($tableListData['columns']) + 1 }}" class="no-padding">

                <div class="row">

                    <form id="line_search_form" role="form" method="GET" action="{{ route($tableListData['routes']['index']['route'], $tableListData['routes']['index']['params']) }}">

                        {{-- lines choice --}}
                        <div class="col-sm-6 table-commands">
                            @if($tableListData['enable_lines_choice'])
                                <span class="input-group">
                                    <button class="hidden" type="submit"></button>
                                </span>
                                <span class="input-group lines">
                                    <span class="input-group-addon" for="input_lines"><i class="fa fa-list-ol"></i></span>
                                    <input id="input_lines" class="form-control" type="number" name="lines" value="{{ $tableListData['lines'] }}" placeholder="{{ trans('global.table_list.placeholder.lines') }}">
                                </span>
                            @endif
                        </div>

                        {{-- search --}}
                        <div class="col-sm-6 table-commands text-right">
                            @if(
                                isset($tableListData['search_config'])
                                && !empty($tableListData['search_config'])
                            )
                                <span class="input-group search">
                                    <span class="input-group-addon" for="input_search">
                                        <i class="fa fa-search"></i>
                                    </span>
                                    <input id="input_search" class="form-control" type="text" name="search" value="{{ $tableListData['search'] }}" placeholder="{{ trans('global.table_list.placeholder.search') }} {{ implode(', ', array_pluck($tableListData['search_config'], 'key')) }}">
                                    @if($tableListData['search'])
                                        <span class="input-group-addon">
                                            <a href="{{ route($tableListData['routes']['index']['route'], array_merge(
                                                [
                                                    'search' => null,
                                                    'lines' => $tableListData['lines']
                                                ],
                                                $tableListData['routes']['index']['params']
                                            )) }}">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        </span>
                                    @endif
                                </span>
                            @endif
                        </div>
                    </form>
                </div>
            </td>
        </tr>
    @endif

    {{-- titles --}}
    <tr>
        @foreach($tableListData['columns'] as $column)
            <th>
                @if(isset($column['sort_by']))
                    <a href="{{ route($tableListData['routes']['index']['route'], array_merge(
                            $tableListData['routes']['index']['params'],
                            [
                                'sort-by' => $column['sort_by'],
                                'search'   => $tableListData['search'],
                                'lines'    => $tableListData['lines'],
                                'sort-dir' => !$tableListData['sort_dir'],
                            ]
                        )) }}" class="spin-on-click">
                        @if(
                            $tableListData['sort_by'] === $column['sort_by']
                            && $tableListData['sort_dir']
                        )
                            <i class="fa fa-sort-asc"></i>
                        @elseif(
                            $tableListData['sort_by'] === $column['sort_by']
                            && !$tableListData['sort_dir']
                        )
                            <i class="fa fa-sort-desc"></i>
                        @else
                            <i class="fa fa-sort"></i>
                        @endif
                        {{ $column['title'] }}
                    </a>
                @else
                    {{ $column['title'] }}
                @endif
            </th>
        @endforeach
        @if(
                (
                    isset($tableListData['routes']['show'])
                    && !empty($tableListData['routes']['show'])
                ) || (
                    isset($tableListData['routes']['edit'])
                    && !empty($tableListData['routes']['edit'])
                ) || (
                    isset($tableListData['routes']['destroy']) && !empty($tableListData['routes']['destroy'])
                )
            )
            <th class="text-right">{{ trans('global.table_list.column.actions') }}</th>
        @endif
    </tr>
    </thead>

    {{-- content --}}
    <tbody>
    @foreach($tableListData['pagination'] as $entity)
        <tr>
            @foreach($tableListData['columns'] as $column)
                <td>

                    {{-- show value from config --}}
                    @if(
                        isset($column['key']) && isset($column['config'])
                        && isset($column['trans'])
                        && !empty(config($column['config'] . '.' . $entity->getAttribute($column['key'])))
                        && !empty($column['trans'])
                    )
                        @if(
                            isset($column['button'])
                            && !empty($column['button'])
                            && $entity->getAttribute($column['key'])
                        )
                            <button class="btn
                            @if(
                                isset($column['button']['attribute'])
                                && !empty($column['button']['attribute'])
                                && $column['button']['attribute'] === 'key'
                            )
                                {{ config($column['config'] . '.' . $entity->getAttribute($column['key'])) }}
                            @elseif(
                                isset($column['button']['attribute'])
                                && !empty($column['button']['attribute'])
                            )
                                {{ $column['button']['attribute'] }}
                            @endif">
                                @endif
                                {{ trans($column['trans'] . '.' . config($column['config'] . '.' . $entity->getAttribute($column['key']))) }}
                                @if(
                                    isset($column['button'])
                                    && $column['button'] === true
                                    && $entity->getAttribute($column['key'])
                                )
                            </button>
                        @endif

                        {{-- show value(s) from relation --}}
                    @elseif(
                        isset($column['key'])
                        && is_a($entity->getAttribute($column['key']), '\Illuminate\Database\Eloquent\Model')
                        && isset($column['relation']['attributes'])
                        && !empty($column['relation']['attributes'])
                    )
                        @foreach($column['relation']['attributes'] as $attribute)
                            @if($entity->getAttribute($column['key'])->$attribute)
                                {{ $entity->getAttribute($column['key'])->$attribute }}
                            @endif
                        @endforeach

                        {{-- show value from collection --}}
                    @elseif(
                        isset($column['key'])
                        && is_a($entity->getAttribute($column['key']), '\Illuminate\Database\Eloquent\Collection')
                        && isset($column['collection'])
                        && !empty($column['collection'])
                    )
                        @foreach($entity->getAttribute($column['key']) as $object)
                            @if(
                                isset($column['button'])
                                && !empty($column['button'])
                                && $object->getAttribute($column['collection'])
                            )
                                <button class="btn
                                @if(
                                    isset($column['button']['attribute'])
                                    && !empty($column['button']['attribute'])
                                )
                                {{ $object->getAttribute($column['button']['attribute']) }}
                                @endif">
                                    @endif
                                    @if($object->getAttribute($column['collection']))
                                        {{ $object->getAttribute($column['collection']) }}
                                    @endif
                                    @if(
                                        isset($column['button']['attribute'])
                                        && !empty($column['button']['attribute'])
                                        && !empty($object->getAttribute($column['button']['attribute']))
                                    )
                                </button>
                            @endif
                        @endforeach

                        {{-- show activation toggle --}}
                    @elseif(
                        isset($column['key'])
                        && isset($column['activate'])
                        && !empty($column['activate'])
                        && !empty($column['activate']['route'])
                    )
                        <form role="form" id="form_activate_{{ $entity->id }}" class="form-inline" method="POST" action="{{ route($column['activate']['route'], $column['activate']['params']) }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_id" value="{{ $entity->id }}">

                            <div class="swipe-group">
                                <input class="swipe" id="activate_{{ $entity->id }}" type="checkbox" name="activation_order" @if($entity->getAttribute($column['key'])) checked @endif>
                                <label class="swipe-btn activate" for="activate_{{ $entity->id }}"></label>
                            </div>
                        </form>

                        {{-- show image --}}
                    @elseif(
                        isset($column['image'])
                        && !empty($image = $column['image'])
                        && !empty($entity->getAttribute($column['key']))
                    )
                        <a href="{{ $entity->imagePath($entity->getAttribute($column['key']), $column['key'], $image['size']['detail']) }}" data-lity>
                            <img class="img-thumbnail @if(isset($image['class'])){{ $image['class'] }} @endif" src="{{ $entity->imagePath($entity->getAttribute($column['key']), $column['key'], $image['size']['thumbnail']) }}">
                        </a>

                        {{-- show formatted date --}}
                    @elseif(
                        isset($column['date'])
                        && !empty($column['date'])
                        && !empty($entity->getAttribute($column['key']))
                    )
                        {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $entity->getAttribute($column['key']))->format($column['date']) }}

                        {{-- show link --}}
                    @elseif(
                        isset($column['link'])
                        && !empty($column['link'])
                    )
                        <a @if(
                            isset($column['link']['id'])
                            && !empty($column['link']['id'])
                            && !empty($column['link']['id']['label'])
                            && !empty($column['link']['id']['attribute'])
                            && $entity->getAttribute($column['link']['id']['attribute'])
                        )
                           id="{{ $column['link']['id']['label'] . $entity->getAttribute($column['link']['id']['attribute']) }}"
                           @endif
                           href="@if(
                            isset($column['link']['url']['route'])
                            && isset($column['link']['url']['params'])
                            && !empty($column['link']['url']['route'])
                            && !empty($column['link']['url']['params'])
                            && !empty($column['link']['url']['params']['key'])
                            && !empty($column['link']['url']['params']['attribute'])
                        ){{ route($column['link']['url']['route'], [$column['link']['url']['params']['key'] => $entity->getAttribute($column['link']['url']['params']['attribute'])]) }}
                           @else#
                        @endif">
                            @if(
                                isset($column['button'])
                                && !empty($column['button'])
                            )
                                <button class="btn @if(isset($column['button']['class'])){{ $column['button']['class'] }}@endif">
                                    @endif
                                    @if(isset($column['link']['label']))
                                        {!! $column['link']['label'] !!}
                                    @endif
                                    @if(
                                        isset($column['button'])
                                        && !empty($column['button'])
                                    )
                                </button>
                            @endif
                        </a>

                        {{-- show value --}}
                    @else
                        @if(
                            isset($column['button'])
                            && $column['button'] === true
                            && !empty($entity->getAttribute($column['key']))
                        )
                            <button class="btn {{ $column['key'] }}">
                                @endif
                                @if(
                                    isset($column['key'])
                                    && isset($column['str_limit'])
                                    && is_numeric($column['str_limit'])
                                )
                                    {{ str_limit(strip_tags($entity->getAttribute($column['key']), $column['str_limit'])) }}
                                @elseif(isset($column['key']))
                                    {{ $entity->getAttribute($column['key']) }}
                                @endif
                                @if(
                                    isset($column['button'])
                                    && $column['button'] === true
                                    && !empty($entity->getAttribute($column['key']))
                                )
                            </button>
                        @endif
                    @endif
                </td>
            @endforeach

            {{-- actions --}}
            @if(
                (
                    isset($tableListData['routes']['show'])
                    && !empty($tableListData['routes']['show'])
                ) || (
                    isset($tableListData['routes']['edit'])
                    && !empty($tableListData['routes']['edit'])
                ) || (
                    isset($tableListData['routes']['destroy']) && !empty($tableListData['routes']['destroy'])
                )
            )
                <td class="actions text-right">

                    {{-- show --}}
                    @if(
                        isset($tableListData['routes']['show'])
                        && !empty($tableListData['routes']['show'])
                        && !empty($tableListData['routes']['show']['route'])
                    )
                        <form role="form" class="form-inline" method="GET" action="{{ route($tableListData['routes']['show']['route'], array_merge(
                            $tableListData['routes']['show']['params'],
                            ['id' => $entity->id]
                        )) }}">
                            <a href="#" class="submit-form">
                                <button class="btn btn-primary btn-rounded btn-sm spin-on-click" name="edit_{{ $entity->id }}">
                                    <i class="fa fa-search" title="{{ trans('global.action.show') }}"></i>
                                </button>
                            </a>
                        </form>
                    @endif

                    {{-- edit --}}
                    @if(
                        isset($tableListData['routes']['edit'])
                        && !empty($tableListData['routes']['edit'])
                        && !empty($tableListData['routes']['edit']['route'])
                    )
                        <form role="form" class="form-inline" method="GET" action="{{ route($tableListData['routes']['edit']['route'], array_merge(
                            $tableListData['routes']['edit']['params'],
                            ['id' => $entity->id]
                        )) }}">
                            <a href="#" class="submit-form">
                                <button class="btn btn-primary btn-rounded btn-sm spin-on-click" name="edit_{{ $entity->id }}">
                                    <i class="fa fa-pencil" title="{{ trans('global.action.edit') }}"></i>
                                </button>
                            </a>
                        </form>
                    @endif

                    {{-- delete --}}
                    @if(
                        isset($tableListData['routes']['destroy'])
                        && !empty($tableListData['routes']['destroy'])
                        && !empty($tableListData['routes']['destroy']['route'])
                    )
                        <form role="form" id="delete_{{ $entity->id }}" class="form-inline" method="POST" action="{{ route($tableListData['routes']['destroy']['route'], array_merge(
                            $tableListData['routes']['edit']['params'],
                            ['id' => $entity->id]
                        )) }}">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_id" value="{{ $entity->id }}">
                            <a href="#" class="confirm" data-confirm="@foreach($confirm['attributes'] as $attribute){{ $entity->getAttribute($attribute) }} @endforeach">
                                <button class="btn btn-danger btn-rounded btn-sm">
                                    <i class="fa fa-times" title="{{ trans('global.action.delete') }}"></i>
                                </button>
                            </a>
                        </form>
                    @endif
                </td>
            @endif
        </tr>
    @endforeach

    {{-- status --}}
    @if($tableListData['pagination']->isEmpty())
        <tr>
            <td colspan="{{ sizeof($tableListData['columns']) + 1 }}" class="text-center">
                <span class="text-info">
                    {!! config('settings.info_icon') !!}
                </span>
                {{ trans('global.table_list.results.empty') }}
            </td>
        </tr>
    @endif
    </tbody>

    <tfoot>

    <tr>

        <td colspan="{{ sizeof($tableListData['columns']) + 1 }}" class="no-padding">

            <div class="row">

                @if(
                    isset($tableListData['routes']['create'])
                    && !empty($tableListData['routes']['create'])
                    && !empty($tableListData['routes']['create']['route'])
                )
                    <div class="col-sm-4 table-commands">
                        <a href="{{ route($tableListData['routes']['create']['route']) }}">
                            <button class="btn btn-success spin-on-click"><i class="fa fa-plus-circle"></i> {{ trans('global.action.add') }}</button>
                        </a>
                    </div>
                @endif

                <div class="col-sm-4 table-nav-infos text-center">
                    @if(isset($tableListData['nav_infos']) && !$tableListData['pagination']->isEmpty())
                        {!! $tableListData['nav_infos'] !!}
                    @endif
                </div>

                <div class="col-sm-4 text-right">
                    @if(is_a($tableListData['pagination'], '\Illuminate\Pagination\LengthAwarePaginator'))
                        {!! $tableListData['pagination']->render() !!}
                    @endif
                </div>
            </div>
        </td>
    </tr>
    </tfoot>
</table>