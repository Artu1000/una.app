<table class="table table-striped table-hover table-list">

    <thead>

        <tr>

            <td colspan="{{ sizeof($tableListData['columns']) + 1 }}" class="no-padding">

                <div class="row">

                    <form role="form" method="GET" action="{{ route($tableListData['route'] . '.index') }}">

                        <div class="col-sm-6 table-commands">

                            <span class="input-group">
                                <button class="hidden" type="submit"></button>
                            </span>
                            <span class="input-group lines">
                                <span class="input-group-addon" for="input_lines"><i class="fa fa-list-ol"></i></span>
                                <input id="input_lines" class="form-control" type="number" name="lines" value="{{ $tableListData['lines'] }}" placeholder="Nombre de lignes">
                            </span>
                        </div>

                        <div class="col-sm-6 table-commands text-right">
                            <span class="input-group search">
                                    <span class="input-group-addon" for="input_search"><i class="fa fa-search"></i></span>
                                    <input id="input_search" class="form-control" type="text" name="search" value="{{ $tableListData['search'] }}" placeholder="Rechercher">
                                @if($tableListData['search'])
                                    <span class="input-group-addon"><a href="{{ route($tableListData['route'] . '.index', ['search' => null, 'lines' => $tableListData['lines']]) }}"><i class="fa fa-times"></i></a></span>
                                @endif
                            </span>
                        </div>
                    </form>
                </div>
            </td>
        </tr>

        <tr>

            @foreach($tableListData['columns'] as $column)
                <th>
                    @if(isset($column['sort_by']))
                        <a href="{{ route($tableListData['route'] . '.index', ['search' => $tableListData['search'], 'lines' => $tableListData['lines'], 'sort-by' => $column['sort_by'], 'sort-dir' => !$tableListData['sort_dir']]) }}">
                            {{ $column['title'] }}
                            @if($tableListData['sort_by'] === $column['sort_by'] && $tableListData['sort_dir']) <i class="fa fa-sort-asc"></i>
                            @elseif($tableListData['sort_by'] === $column['sort_by'] && !$tableListData['sort_dir']) <i class="fa fa-sort-desc"></i>
                            @else <i class="fa fa-sort"></i>
                            @endif
                        </a>
                    @else
                        {{ $column['title'] }}
                    @endif
                </th>
            @endforeach

            <th class="text-right">Actions</th>
        </tr>
    </thead>

    <tbody>

        @foreach($tableListData['pagination'] as $entity)

            <tr>

                @foreach($tableListData['columns'] as $column)
                    <td>
                        @if(isset($column['config']) && !empty(config($column['config'] . '.' . $entity->getAttribute($column['key']))))
                            @if(isset($column['button']) && $column['button'] === true)
                                <button class="btn {{ config($column['config'] . '.' . $entity->getAttribute($column['key']) . '.' . 'key') }}">
                            @endif
                            {{ config($column['config'] . '.' . $entity->getAttribute($column['key']) . '.' . 'title') }}
                        @elseif(is_a($entity->getAttribute($column['key']), '\Illuminate\Database\Eloquent\Collection') && isset($column['collection']) && !empty($column['collection']))
                            @foreach($entity->getAttribute($column['key']) as $object)
                                @if(isset($column['button']['attribute']) && !empty($column['button']['attribute']))
                                    <button class="btn {{ $object->getAttribute($column['button']['attribute']) }}">
                                @endif
                                {{ $object->getAttribute($column['collection']) }}
                            @endforeach
                        @else
                            @if(isset($column['button']) && $column['button'] === true)
                                <button class="btn {{ $column['key'] }}">
                            @endif
                            {{ $entity->getAttribute($column['key']) }}
                        @endif
                        @if(isset($column['button']['attribute']) && !empty($column['button']['attribute']))
                            </button>
                        @endif
                    </td>
                @endforeach

                <td class="actions text-right">

                    <form role="form" class="form-inline" method="GET" action="{{ route($tableListData['route'] . '.show', $entity->id) }}">
                        <a href="#" class="text-info submit-form spin-on-click"><i class="fa fa-pencil-square" title="Mettre à jour"></i></a>
                    </form>

                    <form role="form" class="form-inline" method="POST" action="{{ route($tableListData['route'] . '.destroy') }}">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_id" value="{{ $entity->id }}">
                        <a href="#" class="text-danger confirm" data-confirm="{{ $entity->getAttribute($confirm['attribute']) }}"
                        ><i class="fa fa-trash" title="Supprimer"></i></a>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>

    <tfoot>

        <tr>

            <td colspan="{{ sizeof($tableListData['columns']) + 1 }}" class="no-padding">

                <div class="row">

                    <div class="col-sm-4 table-commands">
                        <a href="{{ route($tableListData['route'] . '.create') }}">
                            <button class="btn btn-success spin-on-click"><i class="fa fa-plus-circle"></i> Ajouter</button>
                        </a>
                    </div>

                    <div class="col-sm-4 table-nav-infos text-center">
                        <b>{{ $tableListData['nav_infos'] }}</b>
                    </div>

                    <div class="col-sm-4 text-right">
                        {!! $tableListData['pagination']->render() !!}
                    </div>
                </div>
            </td>
        </tr>
    </tfoot>
</table>