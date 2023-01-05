<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tip;
use App\Models\Game;
use App\Models\Ladder;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LaddersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $nextGame = Game::getNextGame();
        $rounds = Game::getAllRoundNumbers();
        $games = (count($nextGame) > 0) ? Game::getRound($nextGame[0]->round) : Game::getRound(last($rounds));
        $logos = Game::getTeamLogos();
        $ladder = Ladder::getLadder();
        $powerTips = Tip::getAllUserPowerTips();
        
        return view('ladder', ['games' => $games, 'rounds' => $rounds, 'logos' => $logos, 'ladder' => $ladder, 'allPowerTips' => $powerTips]);
    }

    public function ladderAdmin()
    {
        $nextGame = Game::getNextGame();
        $rounds = Game::getAllRoundNumbers();
        $games = (count($nextGame) > 0) ? Game::getRound($nextGame[0]->round) : Game::getRound(last($rounds));
        $logos = Game::getTeamLogos();
        $ladder = Ladder::getLadder();
        
        return view('admin.ladderAdmin', ['games' => $games, 'rounds' => $rounds, 'logos' => $logos, 'ladder' => $ladder]);
    }

    public function saveLadderAdmin() {

        $l = (['id' => request()->input('id'),
                'roundPoints' => request()->input('roundPoints'),
                'totalPoints' => request()->input('totalPoints'),
                'powerTip' => request()->input('powerTip'),
                'paid' => request()->input('paid')]);

        Ladder::saveLadder($l);
       
        return redirect()->route('ladderAdmin');
    }

    public function processLadder($round, $trigger, $gameId) {

        $year = Game::getYear();      
        $tipsThisRound = Tip::getRoundTips($round);
        $game = Game::getGame($gameId);

        if($trigger == 'first') {
            $this->resetLadder($year[0]->year,$round);
        }

        foreach ($tipsThisRound as $tip) {
            $ladderRecord = Ladder::getLadderForUser($tip->userId);
            if (count($ladderRecord)) {
                $points = ['roundPoints' => $ladderRecord[0]->roundPoints, 'total' => $ladderRecord[0]->totalPoints];
            } else {
                $points = ['roundPoints' => 0, 'total' => 0];
            }
            
            // calculate tip points
            $tipPoints = $this->processPoints($tip->teamTipped, $game, $points, $tip->powerTip);
            
            // update or insert into ladder
            if (count($ladderRecord)) {
                Ladder::where('id', $ladderRecord[0]->id)
                    ->update([
                        'round' => $tip->round,
                        'teamTipped' => $tip->teamTipped,
                        'roundPoints' => $tipPoints['roundPoints'],
                        'totalPoints' => $tipPoints['total'],
                        'powerTip' => $tip->powerTip
                    ]);
            } else {
                Ladder::create([
                    'year' => $year[0]->year,
                    'round' => $round,
                    'teamTipped' => $tip->teamTipped,
                    'powerTip' => $tip->powerTip,
                    'roundPoints' => $tipPoints['roundPoints'],
                    'totalPoints' => $tipPoints['total'],
                    'userId' => $tip->userId,
                    'paid' => false,
                    'disabled' => false
                ]);
            }

            //update tip points
            Tip::where('id', $tip->id)
                ->update(['points' => $tipPoints['roundPoints']]);
        }

        if($trigger == 'last') {
            $this->calcNoTips($year[0]->year,$round);
        }

    }

    public function resetLadder($year, $r) {
        
        Ladder::where('year', $year)
                    ->update([
                        'round' => $r,
                        'teamTipped' => '',
                        'roundPoints' => 0
                    ]);
    }

    public function processPoints($teamTipped, $game, $points, $powerTip) {
        
        if ($teamTipped == $game[0]->homeTeam) {
            $thisPoints = ($powerTip) ? (($game[0]->homeTeamScore - $game[0]->awayTeamScore) * 2) : ($game[0]->homeTeamScore - $game[0]->awayTeamScore);
            $points['roundPoints'] = $thisPoints;
            $points['total'] = $points['total'] + $points['roundPoints'];
        } else if ($teamTipped == $game[0]->awayTeam) {
            $thisPoints = ($powerTip) ? (($game[0]->awayTeamScore - $game[0]->homeTeamScore) * 2) : ($game[0]->awayTeamScore - $game[0]->homeTeamScore);
            $points['roundPoints'] = $thisPoints;
            $points['total'] = $points['total'] + $points['roundPoints'];
        }
        
        return $points;
    }

    public function calcNoTips($year, $r) {
        
        $emptyTips = Ladder::getEmptyTipsForRound($r);

        foreach ($emptyTips as $e) {
            
            //add tip
            Tip::create([
                'userId' => $e->userId,
                'round' => $r,
                'year' => $year,
                'teamTipped' => 'No Tip',
                'points' => -10,
                'superceded' => 0,
                'powerTip' => 0]);

            //update ladder
            $total = ($e->totalPoints - 10);
            Ladder::where(['userId' => $e->userId,'round' => $r, 'year' => $year])
                ->update(['roundPoints' => -10,
                        'teamTipped' => 'No Tip',
                        'totalPoints' => $total]);
        }
        
    }


    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
