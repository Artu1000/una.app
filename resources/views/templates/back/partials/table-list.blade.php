<table class="table table-striped table-hover table-list">

    <thead>

        @if($tableListData['enable_lines_choice'] || !empty($tableListData['search_config']))
            <tr>

                <td colspan="{{ sizeof($tableListData['columns']) + 1 }}" class="no-padding">

                    <div class="row">

                        <form role="form" method="GET" action="{{ route($tableListData['routes']['index']) }}">

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

                            <div class="col-sm-6 table-commands text-right">
                                @if(!empty($tableListData['search_config']))
                                    <span class="input-group search">
                                            <span class="input-group-addon" for="input_search"><i class="fa fa-search"></i></span>
                                            <input id="input_search" class="form-control" type="text" name="search" value="{{ $tableListData['search'] }}" placeholder="{{ trans('global.table_list.placeholder.search') }}">
                                        @if($tableListData['search'])
                                            <span class="input-group-addon"><a href="{{ route($tableListData['routes']['index'], ['search' => null, 'lines' => $tableListData['lines']]) }}"><i class="fa fa-times"></i></a></span>
                                        @endif
                                    </span>
                                @endif
                            </div>
                        </form>
                    </div>
                </td>
            </tr>
        @endif

        <tr>

            @foreach($tableListData['columns'] as $column)
                <th>
                    @if(isset($column['sort_by']))
                        <a href="{{ route($tableListData['routes']['index'], ['search' => $tableListData['search'], 'lines' => $tableListData['lines'], 'sort-by' => $column['sort_by'], 'sort-dir' => !$tableListData['sort_dir']]) }}" class="spin-on-click">
                            @if($tableListData['sort_by'] === $column['sort_by'] && $tableListData['sort_dir']) <i class="fa fa-sort-asc"></i>
                            @elseif($tableListData['sort_by'] === $column['sort_by'] && !$tableListData['sort_dir']) <i class="fa fa-sort-desc"></i>
                            @else <i class="fa fa-sort"></i>
                            @endif
                            {{ $column['title'] }}
                        </a>
                    @else
                        {{ $column['title'] }}
                    @endif
                </th>
            @endforeach

            <th class="text-right">{{ trans('global.table_list.column.actions') }}</th>
        </tr>
    </thead>

    <tbody>

        @foreach($tableListData['pagination'] as $entity)

            <tr>

                @foreach($tableListData['columns'] as $column)
                    <td>
                        {{-- show value from config --}}
                        @if(isset($column['config']) && !empty(config($column['config'] . '.' . $entity->getAttribute($column['key']))))
                            @if(isset($column['button']) && $column['button'] === true)
                                <button class="btn {{ config($column['config'] . '.' . $entity->getAttribute($column['key']) . '.' . 'key') }}">
                            @endif
                            {{ config($column['config'] . '.' . $entity->getAttribute($column['key']) . '.' . 'title') }}
                            @if(isset($column['button']) && $column['button'] === true)
                                </button>
                            @endif
                        {{-- show value from collection --}}
                        @elseif(is_a($entity->getAttribute($column['key']), '\Illuminate\Database\Eloquent\Collection') && isset($column['collection']) && !empty($column['collection']))
                            @foreach($entity->getAttribute($column['key']) as $object)
                                @if(isset($column['button']['attribute']) && !empty($column['button']['attribute']))
                                    <button class="btn {{ $object->getAttribute($column['button']['attribute']) }}">
                                @endif
                                {{ $object->getAttribute($column['collection']) }}
                                @if(isset($column['button']['attribute']) && !empty($column['button']['attribute']))
                                    </button>
                                @endif
                            @endforeach
                        {{-- show activation toggle --}}
                        @elseif(isset($column['activate']) && !empty($column['activate']))
                            <div class="swipe-group">
                                <input class="swipe" id="activate_{{ $entity->id }}" type="checkbox" name="{{ $entity->id }}" @if($entity->getAttribute($column['key'])) checked @endif>
                                <label class="swipe-btn activate" data-url="{{ route($column['activate']) }}" data-id="{{ $entity->id }}" for="activate_{{ $entity->id }}"></label>
                            </div>
                        {{-- show image --}}
                        @elseif(isset($column['image']) && !empty($image = $column['image']) && !empty($entity->getAttribute($column['key'])))
                            <a href="{{ route('image', [
                                'filename' => $entity->getAttribute($column['key']),
                                'storage_path' => $image['storage_path'],
                                'size' => $image['size']['detail']
                                ]) }}" data-lity>
                                <img width="40" height="40" src="{{ route('image', [
                                'filename' => $entity->getAttribute($column['key']),
                                'storage_path' => $image['storage_path'],
                                'size' => $image['size']['thumbnail']
                                ]) }}">
                            </a>
                        {{-- show value --}}
                        @else
                            @if(isset($column['button']) && $column['button'] === true && !empty($entity->getAttribute($column['key'])))
                                <button class="btn {{ $column['key'] }}">
                            @endif
                            {{ $entity->getAttribute($column['key']) }}
                            @if(isset($column['button']) && $column['button'] === true && !empty($entity->getAttribute($column['key'])))
                                </button>
                            @endif
                        @endif
                    </td>
                @endforeach

                <td class="actions text-right">

                    <form role="form" class="form-inline" method="GET" action="{{ route($tableListData['routes']['edit'], $entity->id) }}">
                        <a href="#" class="text-info submit-form spin-on-click"><i class="fa fa-pencil-square" title="{{ trans('global.action.edit') }}"></i></a>
                    </form>

                    <form role="form" class="form-inline" method="POST" action="{{ route($tableListData['routes']['destroy']) }}">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_id" value="{{ $entity->id }}">
                        <a href="#" class="text-danger confirm" data-confirm="@foreach($confirm['attributes'] as $attribute){{ $entity->getAttribute($attribute) }} @endforeach"><i class="fa fa-trash" title="{{ trans('global.action.delete') }}"></i></a>
                    </form>
                </td>
            </tr>
        @endforeach
        @if($tableListData['pagination']->isEmpty())
            <tr>
                <td colspan="{{ sizeof($tableListData['columns']) + 1 }}" class="text-center">
                    <span class="text-info"><i class="fa fa-info-circle"></i></span> {{ trans('global.table_list.results.empty') }}
                </td>
            </tr>
        @endif
    </tbody>

    <tfoot>

        <tr>

            <td colspan="{{ sizeof($tableListData['columns']) + 1 }}" class="no-padding">

                <div class="row">

                    <div class="col-sm-4 table-commands">
                        <a href="{{ route($tableListData['routes']['create']) }}">
                            <button class="btn btn-success spin-on-click"><i class="fa fa-plus-circle"></i> {{ trans('global.action.add') }}</button>
                        </a>
                    </div>

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