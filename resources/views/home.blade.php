@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="home-banner">
        <div class="row">
            <div class="col"></div>
            <div class="col-9">
                <p>&nbsp;</p>
                <h1 class="text-white display-3">Welcome to <strong>SuperTip!</strong> <span class="badge bg-dark">@isset($year){{ $year }}@endisset</span></h1>
                <h5 class="text-white"><strong>A sports tipping comp with a difference</strong></h5>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                @guest
                    <a class="btn btn-primary btn-lg" href="{{ route('login') }}">Login</a>&nbsp;&nbsp;
                    <a class="btn btn-secondary btn-lg" href="{{ route('register') }}">Register</a>
                @else
                    <a class="btn btn-danger" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();"
                    >Logout</a>&nbsp;&nbsp;
                    <a class="btn btn-success" href="{{ route('tip') }}">Enter a Tip</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                @endguest
            </div>
            <div class="col-2">
                <img src="/images/nrl-badge.svg">
            </div>
        </div>
    </div>
</div>

@isset($nextGame)
<div class="container-fluid">
    <div class="row">
        <div class="col text-end">
            <div class="card">
                <div class="card-body">
                    @if (count($nextGame) > 0)
                        <p>Next Game Lockout Round {{ $nextGame[0]->round }}: <span class="badge bg-danger">{{ $nextGame[0]->formattedDate }} AEST</span></p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">&nbsp;</div>
    <div class="row">
        <div class="col">
            <div class="card text-center">
                <div class="card-body">
                    <h4><span class="badge rounded-pill bg-info text-dark">$25 to play</span></h4>
                    @guest
                        <p class="card-text"></p>
                    @else
                        @isset($ladder)
                            @foreach ($ladder as $l)
                                @if ($l->userId == Auth::user()->id)
                                    @if ($l->paid)
                                        <span class="badge bg-success">You have paid. Thankyou</span>
                                    @else
                                        <h4><span class="badge bg-danger">
                                            Please make payment to:<br/>
                                            Account Name: T Chapman<br/>
                                            BSB: 484-799<br/>
                                            Account No: 161744135
                                        </span></h4>
                                    @endif
                                @endif
                            @endforeach
                        @endisset
                    @endguest
                    <p class="card-text"></p>
                    <h4><strong>Prizes:</strong></h4>
                    <p class="card-text">
                        <span class="badge rounded-pill bg-primary">1st</span>
                        <span class="badge rounded-pill bg-danger">2nd</span>
                        <span class="badge rounded-pill bg-success">3rd</span>
                        <span class="badge rounded-pill bg-secondary">4th</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endisset

<div class="container">    
    <p>&nbsp;</p>
    @guest
        <!-- no dashboard until logged in -->
    @else
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card text-white bg-dark mb-3">
                    <div class="card-header">Your Dashboard</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
                                    <div class="card-header text-center">
                                        @if(count($nextGame) > 0)
                                            Rd {{ $nextGame[0]->round }}<br/>Tip
                                        @else
                                            Last Tip
                                        @endif
                                    </div>
                                    <div class="card-body text-dark bg-white">
                                        @isset($tipThisRound)
                                            @if ($tipThisRound != '' && $tipThisRound !='-' && $tipThisRound != 'No Tip')
                                                <img src="{{ $logos[$tipThisRound] }}" class="h-50">
                                            @elseif ($tipThisRound == 'No Tip')
                                                <p class="card-text text-center">No Tip</p>
                                            @else
                                                <h4 class="card-title text-center">No Tip Yet</h4>
                                            @endif
                                            
                                        @endisset
                                        @isset($tipPoints)
                                            @if ($tipPoints != 0)
                                                <h4 class="card-title text-center">{{ $tipPoints }}</h4>
                                            @endif
                                        @endisset
                                    </div>
                                </div>
                                <div class="card text-white bg-success mb-3" style="max-width: 18rem;">
                                    <div class="card-header text-center">Rank</div>
                                    <div class="card-body">
                                        <h4 class="card-title text-center">
                                            @isset($ladder)
                                                @php
                                                    $rank = 1;
                                                @endphp
                                                @foreach ($ladder as $l)
                                                    @if ($l->userId == Auth::user()->id)
                                                        {{ $rank }}
                                                    @endif
                                                    @php
                                                        $rank++;
                                                    @endphp
                                                @endforeach
                                            @endisset    
                                        </h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="card bg-light">
                                    <div class="card-header text-center text-dark">All Tips</div>
                                    <div class="card-body">
                                        @isset($allTips)
                                        <div class="table-responsive">
                                            <table class="table table-sm table-borderless table-striped">
                                                <tbody>
                                                    @foreach ($allTips as $t)
                                                        @php
                                                            if ($t->points < 0) {
                                                                $cl = 'p-0 text-danger';
                                                            } else {
                                                                $cl = 'p-0 text-success';
                                                            }
                                                        @endphp
                                                        <tr>
                                                            <td class="p-0"><strong>{{ $t->round }}</strong></td>
                                                            <td class="p-0">
                                                                <strong>{{ $t->teamTipped }}</strong>
                                                                &nbsp;
                                                                @if ($t->powerTip)
                                                                    <img src="/images/stars-png-613.png" width="25px" height="25px">
                                                                @endif
                                                            </td>
                                                            <td class="{{ $cl }} text-center">
                                                                @if($t->points != 0)
                                                                    <strong>{{ $t->points }}</strong>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        @endisset
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    @endguest
    
</div>
@endsection
