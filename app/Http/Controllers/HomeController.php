<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Tip;
use App\Models\Ladder;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /*
    public function __construct()
    {
        $this->middleware('auth');
    }*/

    /**
     * Show the application.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $year = Game::getYear();
        $nextGame = Game::getNextGame();
        $logos = Game::getTeamLogos();
        $ladder = Ladder::getLadder();

        if (Auth::check()) {
            $tipThisRound = (count($nextGame) === 0) ? '' : Tip::getTeamTipped($nextGame[0]->round, Auth::user()->id);
            $tipPoints = (count($nextGame) === 0) ? 0 : Tip::getTipPoints($nextGame[0]->round, Auth::user()->id);
            $userTips = Tip::getAllTipsUser(Auth::user()->id);
        } else {
             { }
            $tipThisRound = (count($nextGame) === 0) ? '' : Tip::getTeamTipped($nextGame[0]->round, 0);
            $tipPoints = (count($nextGame) === 0) ? 0 : Tip::getTipPoints($nextGame[0]->round, 0);
            $userTips = Tip::getAllTipsUser(0);
        }
        
        return view('home', ['year' => $year[0]->year, 'nextGame' => $nextGame, 'logos' => $logos
                    , 'tipThisRound' => $tipThisRound, 'tipPoints' => $tipPoints, 'ladder' => $ladder, 'allTips' => $userTips]);
    }


}
