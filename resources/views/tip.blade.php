@extends('layouts.app')

@section('content')
<style>
    input[type=radio]{
        transform:scale(2);
    }
    .saveBtn{
        position: fixed;
        width: 26%;
        bottom: 5%;
        right: 37%;
        z-index: 999;
    }
    @media only screen and (max-device-width: 480px) {
        .saveBtn{
            position: fixed;
            bottom: 5%;
            right: 44%;
            z-index: 999;
        }
    }
</style>
<div class="container">
    <div class="row justify-content-center">
        @guest
            <!-- if not logged in but manage to get to the page -->
            <div class="alert alert-danger" role="alert">
                <p>You must have been logged out!</p>
                <p><a href="{{ route('login') }}">Login</a> again.</p>
            </div>
        @else
            <div class="row">
                <div class="col-6">
                    <h1>Enter Your Tip</h1>
                </div>
                <div class="col-1"></div>
                <div class="col-3">
                    <span class="badge bg-danger" id="counter"></span></p>
                </div>
                <div class="col-2"></div>
            </div>
            <!-- non payment msg -->
            @isset($userLadder)
                @if(count($userLadder) > 0 && !$userLadder[0]->paid)
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <p class="fs-6">
                            You have not paid as yet. Please make payment to:<br/>
                            Account Name: T Chapman<br/>
                            BSB: 484-799<br/>
                            Account No: 161744135
                        </p>
                    </div>
                @endif
            @endisset
            <!-- if just submitted a tip -->
            @if(isset($submittedTip))
                <div class="alert alert-success" role="alert">
                    <p class="fs-5">You tipped {{ $submittedTip['teamTipped'] }} for round {{ $submittedTip['round'] }}.
                        @if ($submittedTip['powerTip'])
                            You also used this round as your PowerTip.
                        @endif
                    </p>
                    <p class="fs-5">Good luck!</p>
                </div>
            <!-- or msg showing who you have tipped this round -->
            @elseif ($tipThisRound != '' && $tipThisRound != '-' && $tipThisRound != 'No Tip')
                <div class="alert alert-success" role="alert">
                    <p class="fs-5">You tipped {{ $tipThisRound }} for round {{ $roundToUse }}.
                    @if (count($powerTip) > 0 && $powerTip[0]->round == $roundToUse)
                        You also used this round as your PowerTip.              
                    @endif
                    </p>
                </div>
            @elseif ($tipThisRound == '-')
                <div class="alert alert-success" role="alert">
                    <p class="fs-5">You entered late and did not have a tip for round {{ $roundToUse }}.</p>
                </div>
            @elseif ($tipThisRound == 'No Tip')
                <div class="alert alert-success" role="alert">
                    <p class="fs-5">You did not enter a tip for round {{ $roundToUse }}.</p>
                </div>
            @endif
            
            <!-- if error -->
            @error('tip')
                <div class="alert alert-danger" role="alert">
                    <p class="fs-6">You did not select a tip. {{ $message }}.</p>
                </div>    
            @enderror

            <h5 class="text-center">Round:</h5>                
            @isset($rounds)
                <ul class="nav justify-content-center">
                @foreach ($rounds as $round)
                    <!-- selected round -->
                    @if ($round->round == $roundToUse)
                        <li class="page-item"><a class="page-link bg-primary text-white" href="/tip/round/{{ $round->round }}">{{ $round->round }}</a></li>
                    @else
                        <li class="page-item"><a class="page-link" href="/tip/round/{{ $round->round }}">{{ $round->round }}</a></li>
                    @endif
                @endforeach
                </ul>
                <p></p>
            @endisset

            @isset($games)
                <div class="row">
                    <div class="col">
                        <div class="card text-white bg-dark mb-3">
                            <div class="card-body">
                                <h3 class="text-center card-title">Round {{ $roundToUse }}</h3>
                            </div>
                            </div>
                    </div>
                </div>
                <form id="tip_form" name="tip_form" action="/tip/save" method="POST">
                    @csrf <!-- this is built in protection for your form post --->

                    <!-- looping games to know if all finished which is needed for powertip option -->
                    @php
                        $count = 0;
                        $alreadyTipped = false;
                        $finishedAllGames = false;
                        $nextGameTimerDate = '2020-01-01';
                    @endphp
                    @foreach ($games as $d)
                        @php                        
                            $gameStarted = false;
                            if(now()->addHour(10) > $d->date) {
                                $gameStarted = true;
                            }
                            $finishedAllGames = false;                            
                            $count++;
                            if (count($games) == $count && $gameStarted) {
                                $finishedAllGames = true;
                            }
                            // if your tip has already played
                            if (($d->homeTeam == $tipThisRound || $d->awayTeam == $tipThisRound) && (($d->homeTeamScore != 0 && $d->awayTeamScore != 0) || $gameStarted)){
                                $alreadyTipped = true;
                            }
                        @endphp
                    @endforeach

                    <!-- If all games finished then hide Save Tip and PowerTip -->
                    @if (!$finishedAllGames)
                        <div class="row">
                            <div class="col text-center">
                                <!-- If powertip already used -->
                                @if(count($powerTip) > 0)
                                    @if ($powerTip[0]->round < $roundToUse)
                                        <div class="card border-info mb-3">
                                            <div class="card-body">
                                                <p class="card-text">You already used your PowerTip in round {{ $powerTip[0]->round }} on the {{ $powerTip[0]->teamTipped }}.</p>
                                            </div>
                                        </div>
                                    @elseif ($powerTip[0]->round == $roundToUse)
                                        <input class="form-check-input" type="checkbox" id="powertip" name="powertip" value="powertip" checked>
                                        <label class="form-check-label" for="powertip">PowerTip</label>
                                    @else
                                        <input class="form-check-input" type="checkbox" id="powertip" name="powertip" value="powertip">
                                        <label class="form-check-label" for="powertip">PowerTip</label>
                                    @endif  
                                @else
                                    <div class="text-center">
                                        <input class="form-check-input" type="checkbox" id="powertip" name="powertip" value="powertip">
                                        <label class="form-check-label" for="powertip"><strong>PowerTip</strong></label>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="row">&nbsp;</div>
                        <div class="row">
                            <div class="col text-center">
                                @if ($tipLastRound == '' && $roundToUse > 1 && count($userTips) > 0)
                                    <div class="card border-danger mb-3">
                                        <div class="card-body">
                                            <p class="card-text">You cannot save a tip this round as you have not yet entered a tip for last round.</p>
                                        </div>
                                    </div>
                                @elseif ($alreadyTipped)
                                    <div class="card border-danger mb-3">
                                        <div class="card-body">
                                            <p class="card-text">You cannot save another tip this round as your team has already played.</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="col">
                                        <div>
                                            <button type="submit" class="btn btn-success btn-lg saveBtn ">Save Tip</button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    @foreach ($games as $d)
                        @php
                            $gameStarted = false;
                            if(now()->addHour(10) > $d->date) {
                                $gameStarted = true;
                            }

                            // disabling for teams that cannot be picked
                            $homeTeamDisabled = false;
                            $awayTeamDisabled = false;
                            $homeDisabledClass = '';
                            $awayDisabledClass = '';
                            if (in_array($d->homeTeam, $noTipTeams)) {
                                $homeTeamDisabled = true;
                                $homeDisabledClass = 'bg-secondary';
                            };
                            if (in_array($d->awayTeam, $noTipTeams)) {
                                $awayTeamDisabled = true;
                                $awayDisabledClass = 'bg-secondary';
                            };

                            // for highlighting team tipped
                            $homeTippedClass = ($d->homeTeam == $tipThisRound) ? 'bg-success' : '';
                            $awayTippedClass = ($d->awayTeam == $tipThisRound) ? 'bg-success' : '';

                            // remove playing teams from byeLogos - for showing bye team(s)
                            unset($byeLogos[$d->homeTeam]);
                            unset($byeLogos[$d->awayTeam]);
                        @endphp

                        <div class="card">
                            <div class="card-header text-center mb-2 bg-primary text-white"><strong>{{ $d->formattedDate }}</strong></div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-3 {{ $homeDisabledClass }} {{ $homeTippedClass }}" id="{{ $d->homeTeam }}_logo">
                                        <p class="text-center"><img src="{{ $logos[$d->homeTeam] }}" style="max-height: 140px;"></p>
                                    </div>
                                    <div class="col d-flex align-items-center justify-content-center">
                                        @if ($gameStarted || ($d->awayTeamScore != 0 && $d->homeTeamScore != 0))
                                            <p class="display-4"><strong>{{ $d->homeTeamScore }}</strong></p>
                                        @elseif ($homeTeamDisabled)
                                            <p class="display-4"></p>
                                        @else
                                            @if ($homeTippedClass != '')
                                                <p>
                                                <input type="radio" class="form-check-input" name="tip" id="{{ $d->homeTeam }}"
                                                    title="Select {{ $d->homeTeam }}" onclick="setTipped(this)" value="{{ $d->homeTeam }}" checked>
                                                </p>
                                            @else
                                                <p>
                                                <input type="radio" class="form-check-input" name="tip" id="{{ $d->homeTeam }}"
                                                title="Select {{ $d->homeTeam }}" onclick="setTipped(this)" value="{{ $d->homeTeam }}">
                                                </p>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="col-1 d-flex align-items-center justify-content-center">
                                        <p class="display-4"><strong>-</strong></p>
                                    </div>
                                    <div class="col d-flex align-items-center justify-content-center">
                                        @if ($gameStarted || ($d->awayTeamScore != 0 && $d->homeTeamScore != 0))
                                            <p class="display-4"><strong>{{ $d->awayTeamScore }}</strong></p>
                                        @elseif ($awayTeamDisabled)
                                            <p class="display-4"></p>
                                        @else
                                            @if ($awayTippedClass != '')
                                                <p>
                                                <input type="radio" class="form-check-input" name="tip" id="{{ $d->awayTeam }}"
                                                title="Select {{ $d->awayTeam }}" onclick="setTipped(this)" value="{{ $d->awayTeam }}" checked>
                                                </p>
                                            @else
                                                <p>    
                                                <input type="radio" class="form-check-input" name="tip" id="{{ $d->awayTeam }}"
                                                title="Select {{ $d->awayTeam }}" onclick="setTipped(this)" value="{{ $d->awayTeam }}">
                                                </p>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="col-3 {{ $awayDisabledClass }} {{ $awayTippedClass }}" id="{{ $d->awayTeam }}_logo">
                                        <p class="text-center"><img src="{{ $logos[$d->awayTeam] }}" style="max-height: 140px;"></p>
                                    </div>
                                    <input type="hidden" id="round" name="round" value="{{ $d->round }}">
                                    <input type="hidden" id="year" name="year" value="{{ $d->year }}">
                                    <input type="hidden" id="userId" name="userId" value="{{ Auth::user()->id }}">
                                </div>
                                <div class="row">
                                    <div class="col-6 border bg-light">
                                        <p></p><strong><p class="text-center fs-6">
                                            {{ $d->homeTeam }}
                                            @isset($tipsCount)
                                                @foreach ($tipsCount as $tip)
                                                    @if ($tip->teamTipped == $d->homeTeam)
                                                        &nbsp;&nbsp;<span class="badge rounded-pill bg-info" title="No of people that tipped this team">{{ $tip->NoTips }}</span>
                                                    @endif
                                                @endforeach
                                            @endisset
                                        </p></strong>
                                    </div>
                                    <!--<div class="col-4"></div>-->
                                    <div class="col-6 border bg-light">
                                        <p></p><strong><p class="text-center fs-6">
                                            {{ $d->awayTeam }}
                                            @isset($tipsCount)
                                                @foreach ($tipsCount as $tip)
                                                    @if ($tip->teamTipped == $d->awayTeam)
                                                        &nbsp;&nbsp;<span class="badge rounded-pill bg-info" title="No of tips">{{ $tip->NoTips }}</span>
                                                    @endif
                                                @endforeach
                                            @endisset
                                        </p></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">&nbsp;</div>
                    @endforeach
                    
                    <!-- Byes -->
                    @if(count($byeLogos) > 0)
                        <div class="card">
                            <div class="card-header text-center"><strong>BYE</strong></div>
                            <div class="card-body">
                                <div class="row">
                                    <p class="text-center">
                                        @foreach ($byeLogos as $l)
                                            <img src="{{ $l }}" style="max-height: 100px;">&nbsp;&nbsp;
                                        @endforeach
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- loop again to set timer for next game -->
                    @foreach ($games as $g)
                        @php
                            if(now()->addHour(10) > $g->date) {
                                $nextGameTimerDate = '2020-01-01';
                            } else {
                                $nextGameTimerDate = $g->date;
                                break;
                            }
                        @endphp
                    @endforeach
                    <input type="hidden" id="nextGameTimerDate" name="nextGameTimerDate" value="{{ $nextGameTimerDate }}">
                    
                </form>
            @endisset
        @endguest
    </div>
</div>

<script>
    function setTipped(team) {
        var els = document.getElementsByClassName("bg-success");
        for(var i = 0; i < els.length; i++)
        {
            thisEl = document.getElementById(els[i].id);
            thisEl.classList.remove("bg-success");
        }
        var logoEle = team.id + '_logo';
        var element = document.getElementById(logoEle);
        element.classList.add("bg-success");
    }

    // Set the date we're counting down to
    //var countDownDate = new Date("Jan 5, 2024 15:37:25").getTime();
    var countDownDate = document.getElementById("nextGameTimerDate").value.replace(/\-/g,'/');
    countDownDate = new Date(countDownDate).getTime();

    // Update the count down every 1 second
    var x = setInterval(function() {

        // Get today's date and time
        var now = new Date().getTime();

        // Find the distance between now and the count down date
        var distance = countDownDate - now;

        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Display the result in the element with id="demo"
        document.getElementById("counter").innerHTML = "<h6>Next Game: <br>" + days + "d " + hours + "h "
        + minutes + "m " + seconds + "s </h6>";

        // If the count down is finished, write some text and reload page to prevent someone tipping that game
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("counter").innerHTML = "FINISHED";
            if (document.getElementById("counter").innerHTML != "FINISHED") {
                location.reload();
            }
        }
    }, 1000);
</script>
    
@endsection

