@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @guest
        <!-- if not logged in but manage to get to the page -->
        <div class="alert alert-danger" role="alert">
            FORBIDDEN!
        </div>
        @else
            <!-- must be admin -->
            @if (Auth::user()->email == 'terry@mychapman.com' || Auth::user()->email == 'peterdanielsmith@hotmail.com')
                <h1>Games</h1>
                <h5 class="text-center">Round:</h5>                
                @isset($rounds)
                    <ul class="nav justify-content-center">
                    @foreach ($rounds as $round)
                        <li class="page-item"><a class="page-link" href="/games/round/{{ $round->round }}">{{ $round->round }}</a></li>
                    @endforeach
                    </ul>
                    <p></p>
                @endisset

                <!-- check if var is set -->
                @isset($games)
                    <div class="row">
                        <div class="col">
                            <div class="card text-white bg-dark mb-3">
                                <div class="card-body">
                                    <h3 class="text-center card-title">Round {{ $games[0]->round }}</h3>
                                </div>
                              </div>
                        </div>
                    </div>
                    @php
                        $count = 0;
                    @endphp
                    @foreach ($games as $d)
                        @php
                            $count++;
                        @endphp
                        <form id="{{ $d->id }}_form" name="{{ $d->id }}_form" action="/games/{{ $d->id }}" method="POST">
                            @csrf <!-- this is built in protection for your form post ---> 
                            
                            <div class="card">
                                <div class="card-header text-center mb-2"><strong>{{ $d->formattedDate }}</strong></div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-3">
                                            <p><img src="{{ $logos[$d->homeTeam] }}" class="h-50"></p>
                                        </div>
                                        <div class="col d-flex align-items-center justify-content-center">
                                            @if($d->homeTeamScore > 0 || $d->awayTeamScore > 0)
                                                <p class="display-4"><strong>{{ $d->homeTeamScore }}</strong></p>
                                            @else
                                                <input type="text" class="form-control w-80" id="homeScore" name="homeScore" value="{{ $d->homeTeamScore }}">
                                            @endif
                                        </div>
                                        <div class="col-1 d-flex align-items-center justify-content-center">
                                            <p class="display-4"><strong>-</strong></p>
                                        </div>
                                        <div class="col d-flex align-items-center justify-content-center">
                                            @if($d->homeTeamScore > 0 || $d->awayTeamScore > 0)
                                                <p class="display-4"><strong>{{ $d->awayTeamScore }}</strong></p>
                                            @else
                                                <input type="text" class="form-control w-80" id="awayScore" name="awayScore" value="{{ $d->awayTeamScore }}">
                                            @endif
                                        </div>
                                        <div class="col-3">
                                            <p><img src="{{ $logos[$d->awayTeam] }}" class="h-50"></p>
                                        </div>
                                        <input type="hidden" id="id" name="id" value="{{ $d->id }}">
                                        <input type="hidden" id="round" name="round" value="{{ $d->round }}">
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <p></p><strong><p class="text-center fs-6">{{ $d->homeTeam }}</p></strong>
                                        </div>
                                        <div class="col-4"></div>
                                        <div class="col-4">
                                            <p></p><strong><p class="text-center fs-6">{{ $d->awayTeam }}</p></strong>
                                        </div>
                                    </div>
                                </div>
                                @if(($d->homeTeamScore > 0 && $d->awayTeamScore > 0) || ($d->homeTeamScore > 0 || $d->awayTeamScore > 0))
                                    <!-- nothing leave empty - no save button -->
                                @else
                                    <div class="card-footer">
                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="row">&nbsp;</div>
                            @if ($count == 1)
                                <input type="hidden" id="firstGame" name="firstGame" value="{{ $d->id }}">
                            @elseif ($count == count($games))
                                <input type="hidden" id="lastGame" name="lastGame" value="{{ $d->id }}">
                            @endif
                        </form>  
                    @endforeach
                @endisset

            <!-- else forbidden access -->
            @else
                <div class="alert alert-danger" role="alert">
                    FORBIDDEN!
                </div>
            @endif
        @endguest
    </div>
</div>
    
@endsection

