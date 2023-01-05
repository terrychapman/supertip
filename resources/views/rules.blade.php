@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        
        <h1>Rules</h1>
        <div class="card">
            <div class="card-body">
                <ol>
                    <li>Tip one team (only) for each round.</li>
                    <li>Point allocation is decided by the winning margin for your selected team. Point tallies can go into negative scores.</li>
                    <ul>
                        <li>If your selected team wins - your tally will be awarded the winning margin.</li>
                        <li>If your selected team loses - your tally will be deducted the winning margin.</li>
                    </ul>
                    <li>You cannot tip the same team 2 weeks in a row. This prevents you from tipping the top team every week.</li>
                    <li>You cannot tip against the same team 2 weeks in a row. This prevents you from tipping the team playing the bottom team every week.</li>
                    <ul>
                        <li>The two teams you cannot tip will be disabled on the Tip screen so they will be unavailable for selection.</li>
                    </ul>
                    <li>You have two (2) PowerTip (double points) rounds to use during the season.</li>
                    <ul>
                        <li>You must use 1 in the first half of the season (rounds 1 to 13) and the other in the 2nd half of the season (rounds 14 to 25).</li>
                        <li>If you do not use your first PowerTip by the completion of round 13 it will be lost. The same applies for your final PowerTip.</li>
                    </ul>
                    <li>Tips can be made throughout the current round but games already played will be unavailable for selection.</li>
                    <li>A no tip for the round will result in a 10 point deduction from your tally.</li>
                </ol>
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <p class="card-text"><strong>Examples:</strong></p>
                         <ul>
                             <li>If you tip the Broncos to beat the Roosters and the final score is 32-20 (Broncos), your tally is awarded 12 points.</li>
                             <li>If you tip the Broncos to win and the final score is 24-18 (Roosters), your tally is deducted 6 points.</li>
                             <li>If your tally was +4 with the above losing margin, your new tally would  be -2.</li>
                         </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
    
@endsection

