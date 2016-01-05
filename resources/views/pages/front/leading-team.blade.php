@extends('templates.front.full_layout')

@section('content')

    <div id="content" class="leading-team row">

        {{-- parallax img --}}
        <div class="parallax_img">
            {{--@if($page->image)--}}
                {{--<div class="background_responsive_img fill" data-background-image="{{ $page->image }}"></div>--}}
            {{--@endif--}}
        </div>

        <div class="text-content">
            <div class="container">
                <h2><i class="fa fa-cogs"></i> L'équipe dirigeante du club Université Nantes Aviron (UNA)</h2>
                <hr>

                @foreach(config('user.board') as $id => $board)
                    <div class="team">
                        <div class="title display-table">
                            <div class="picto table-cell">
                                <div class="table-cell">
                                    <i class="fa fa-cog {{ $board['key'] }} text-info"></i>
                                </div>
                            </div>
                            <h3 class="table-cell">{{ $board['title'] }}</h3>
                        </div>
                        <p class="subtitle quote">@if($id === config('user.board_key.leading-board'))Comprend le président étudiant
                            @elseif($id === config('user.board_key.executive-committee'))Comprend les membres du Bureau étudiant et du Bureau
                            @endif</p>
                        @foreach($team as $member)
                            @if($member->board === $id)
                                <div class="member">
                                    <img width="145" height="160" src="" alt="{{ $member->last_name }} {{ $member->first_name }}">
                                    <h4 class="display-table">
                                    <span class="table-cell">
                                        {{ $member->last_name }}<br>{{ $member->first_name }}
                                    </span>
                                    </h4>
                                    <h5 class="display-table">
                                    <span class="table-cell">
                                        {{ config('user.status.' . $member->status . '.title') }}
                                    </span>
                                    </h5>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endforeach

                <div class="team">
                    <div class="title display-table">
                        <div class="picto table-cell">
                            <div class="table-cell">
                                <i class="fa fa-cog employee"></i>
                            </div>
                        </div>
                        <h3 class="table-cell">Salariés</h3>
                    </div>
                    @foreach($team as $member)
                        @if($member->board === config('user.board_key.employee'))
                            <div class="member">
                                <img width="145" height="160" src="{{ $member->photo }}" alt="{{ $member->last_name }} {{ $member->first_name }}">
                                <h4 class="display-table">
                                    <span class="table-cell">
                                        {{ $member->last_name }}<br>{{ $member->first_name }}
                                    </span>
                                </h4>
                                <h5 class="display-table">
                                    <span class="table-cell">
                                        {{ config('user.status.' . $member->status . '.title') }}
                                    </span>
                                </h5>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@endsection