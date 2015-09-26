@extends('layouts.front.full_layout')

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
                <div class="team text-left">
                    <div class="title display-table">
                        <div class="picto table-cell">
                            <div class="table-cell">
                                <i class="fa fa-cog leading text-info"></i>
                            </div>
                        </div>
                        <h3 class="table-cell">Bureau</h3>
                    </div>
                    @foreach($team as $member)
                        @if($member->board === config('user.board_key.leading-board'))
                            <div class="member">
                                <img width="145" height="160" src="{{ $member->photo }}" alt="{{ $member->last_name }} {{ $member->first_name }}">
                                <h4 class="display-table">
                                    <span class="table-cell">
                                        {{ $member->last_name }}<br>{{ $member->first_name }}
                                    </span>
                                </h4>
                                <h5 class="display-table">
                                    <span class="table-cell">
                                        @if($member->status === config('user.status_key.association-member'))
                                            Membre
                                        @else
                                            {{ config('user.status.' . $member->status . '.title') }}
                                        @endif
                                    </span>
                                </h5>
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="team">
                    <div class="title display-table">
                        <div class="picto table-cell">
                            <div class="table-cell">
                                <i class="fa fa-cog executive"></i>
                            </div>
                        </div>
                        <h3 class="table-cell">Comité Directeur (+ membres du Bureau)</h3>
                    </div>
                    @foreach($team as $member)
                        @if($member->board === config('user.board_key.executive-committee'))
                            <div class="member">
                                <img width="145" height="160" src="{{ $member->photo }}" alt="{{ $member->last_name }} {{ $member->first_name }}">
                                <h4 class="display-table">
                                    <span class="table-cell">
                                        {{ $member->last_name }}<br>{{ $member->first_name }}
                                    </span>
                                </h4>
                                <h5 class="display-table">
                                    <span class="table-cell">
                                        @if($member->status === config('user.status_key.association-member'))
                                            Membre
                                        @else
                                            {{ config('user.status.' . $member->status . '.title') }}
                                        @endif
                                    </span>
                                </h5>
                            </div>
                        @endif
                    @endforeach
                </div>

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