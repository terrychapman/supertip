<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Tip;
use App\Models\Ladder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TipsController extends Controller
{
    public function index() {      
        
        $nextGame = Game::getNextGame();
        $rounds = Game::getAllRoundNumbers();
        $roundToUse = (count($nextGame) > 0) ? $nextGame[0]->round : last($rounds);
        $games =  Game::getRound($roundToUse);
        $logos = Game::getTeamLogos();
        $thisUserId = 0;
        
        if (Auth::check()) {
            $thisUserId = Auth::user()->id;
        }

        $noTipTeams = Tip::getNoTipTeams($games, $thisUserId);
        $tipThisRound = Tip::getTeamTipped($roundToUse, $thisUserId);
        $tipLastRound = Tip::getTeamTipped(($roundToUse -1), $thisUserId);
        $tipsCount = Tip::getTipsCountThisRound($roundToUse);
        $userTips = Tip::getAllTipsUser($thisUserId);
        $userLadder = Ladder::getLadderForUser($thisUserId);

        if ($roundToUse <= 13) {
            $powerTip = Tip::getFirstPowertip($thisUserId);
        } else {
            $powerTip = Tip::get2ndPowertip($thisUserId);
        }
        
        return view('tip', ['games' => $games, 'rounds' => $rounds, 'logos' => $logos, 'roundToUse' => $roundToUse,
                     'powerTip' => $powerTip, 'noTipTeams' => $noTipTeams, 'tipThisRound' => $tipThisRound, 'byeLogos' => $logos,
                     'tipLastRound' => $tipLastRound, 'tipsCount' => $tipsCount, 'userTips' => $userTips, 'userLadder' => $userLadder]);
    }

    public function showRound($round) {
        
        $games = Game::getRound($round);
        $roundToUse = $round;
        $rounds = Game::getAllRoundNumbers();
        $logos = Game::getTeamLogos();
        $thisUserId = 0;
        
        if (Auth::check()) {
            $thisUserId = Auth::user()->id;
        }

        $noTipTeams = Tip::getNoTipTeams($games, $thisUserId);
        $tipThisRound = Tip::getTeamTipped($round, $thisUserId);
        $tipLastRound = Tip::getTeamTipped(($round -1), $thisUserId);
        $tipsCount = Tip::getTipsCountThisRound($round);
        $userTips = Tip::getAllTipsUser($thisUserId);

        if ($round <= 13) {
            $powerTip = Tip::getFirstPowertip($thisUserId);
        } else {
            $powerTip = Tip::get2ndPowertip($thisUserId);
        }
        
        return view('tip', ['games' => $games, 'rounds' => $rounds, 'logos' => $logos, 'roundToUse' => $roundToUse,
                     'powerTip' => $powerTip, 'noTipTeams' => $noTipTeams, 'tipThisRound' => $tipThisRound, 'byeLogos' => $logos,
                     'tipLastRound' => $tipLastRound, 'tipsCount' => $tipsCount, 'userTips' => $userTips]);
    }

    public function saveTip() {
        
        //validation
        $data = request()->validate([
            'tip' => 'required'
        ]);

        // set powertip
        $thisPowertip = false;
        If (request()->has('powertip')) {
            $thisPowertip = true;
        }

        // supercede any previous tips
        $this->supercede(request()->input('year'),request()->input('round'),request()->input('userId'));
        
        Tip::create([
            'year' => request()->input('year'),
            'round' => request()->input('round'),
            'teamTipped' => request()->input('tip'),
            'powerTip' => $thisPowertip,
            'points' => 0,
            'userId' => request()->input('userId'),
            'superceded' => false
        ]);

        $userTips = Tip::getAllTipsUser(request()->input('userId'));
        $this->lateUserCheck($userTips, request()->input('round'));
        

        $games = Game::getRound(request()->input('round'));
        $rounds = Game::getAllRoundNumbers();
        $logos = Game::getTeamLogos();
        $submittedTip = ['round' => request()->input('round'), 'teamTipped' => request()->input('tip'), 'powerTip' => $thisPowertip];
        $noTipTeams = Tip::getNoTipTeams($games, request()->input('userId'));
        $tipThisRound = Tip::getTeamTipped(request()->input('round'), request()->input('userId'));
        $tipLastRound = Tip::getTeamTipped((request()->input('round') -1), request()->input('userId'));
        $tipsCount = Tip::getTipsCountThisRound(request()->input('round'));
        $roundToUse = request()->input('round');

        if (request()->input('round') <= 13) {
            $powerTip = Tip::getFirstPowertip(request()->input('userId'));
        } else {
            $powerTip = Tip::get2ndPowertip(request()->input('userId'));
        }
        
        return view('tip', ['games' => $games, 'rounds' => $rounds, 'logos' => $logos, 'submittedTip' => $submittedTip,
                     'powerTip' => $powerTip, 'noTipTeams' => $noTipTeams, 'tipThisRound' => $tipThisRound, 'roundToUse' => $roundToUse,
                     'tipLastRound' => $tipLastRound, 'tipsCount' => $tipsCount, 'userTips' => $userTips, 'byeLogos' => $logos]);
    }

    public function supercede($year,$round,$userId) {

        Tip::where(['year' => $year, 'userId' => $userId])
        ->where('round', '>=', $round)
        ->update(['superceded' => true, 'powerTip' => false]
        );
    }

    private function lateUserCheck($userTips, $round) {

        // if round 1 has finished (in the past) and user just entered first tip then we need to set - tips for previous rounds
        if ($round > 1 && count($userTips) == 1) {
            for ($i = 1; $i < $round; $i++) {
                Tip::create([
                    'year' => $userTips[0]->year,
                    'round' => $i,
                    'teamTipped' => '-',
                    'powerTip' => false,
                    'points' => 0,
                    'userId' => $userTips[0]->userId,
                    'superceded' => false
                ]);
            }
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
