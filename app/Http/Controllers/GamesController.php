<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Ladder;
use Carbon\Carbon;
use App\Http\Controllers\Ladders;
use Illuminate\Support\Facades\Auth;

class GamesController extends Controller
{
    // main Games page from menu
    public function index() {      
        
        $games = Game::getRound(1);
        $rounds = Game::getAllRoundNumbers();
        $logos = Game::getTeamLogos();
        
        return view('admin.games', ['games' => $games, 'rounds' => $rounds, 'logos' => $logos]);
    }

    // from click of round button on Games page
    public function showRound($round) {
        
        $games = Game::getRound($round);
        $rounds = Game::getAllRoundNumbers();
        $logos = Game::getTeamLogos();
        
        return view('admin.games', ['games' => $games, 'rounds' => $rounds, 'logos' => $logos]);
    }

    // from Save game button
    public function saveGame() {
       
       Game::where('id', request()->input('id'))
        ->update([
            'homeTeamScore' => request()->input('homeScore'),
            'awayTeamScore' => request()->input('awayScore')
        ]);

        if (!is_null(request()->input('firstGame'))) {
            $trigger = 'first';
        } else if (!is_null(request()->input('lastGame'))) {
            $trigger = 'last';
        } else {
            $trigger = 'none';
        }

        (new LaddersController)->processLadder(request()->input('round'), $trigger, request()->input('id'));

        $games = Game::getRound(request()->input('round'));
        $rounds = Game::getAllRoundNumbers();
        $logos = Game::getTeamLogos();

        return view('admin.games', ['games' => $games, 'rounds' => $rounds, 'logos' => $logos]);
    }

    // import games screen
    public function importGames() {
        
        $data = request()->validate([
            'importGamesFile' => 'required|mimes:csv'
        ]);
    
        $path = request()->file('importGamesFile')->getRealPath();
        $fileData = $this->readCSV($path,array('delimiter' => ','));

        foreach ($fileData as $key => $value) {
            if ($key == 0) {
                // do nothing with header
            } else {
                $this->saveImportGame($value);
            }
        }

        $games = Game::getRound(1);
        $rounds = Game::getAllRoundNumbers();
        $logos = Game::getTeamLogos();

        return view('admin.games', ['games' => $games, 'rounds' => $rounds, 'logos' => $logos]);

    }

    private function readCSV($csvFile, $array)
    {
        // https://stackoverflow.com/questions/52948387/php-laravel-read-csv
        $file_handle = fopen($csvFile, 'r');
        while (!feof($file_handle)) {
            $line_of_text[] = fgetcsv($file_handle, 0, $array['delimiter']);
        }
        fclose($file_handle);
        return $line_of_text;
    }

    private function saveImportGame($data) {

        // get year from Date column
        $date = Carbon::createFromFormat('d/m/Y H:i', $data[2]);
        $yr = $date->format('Y');
        
        Game::create([
            'year' => $yr,
            'round' => $data[1],
            'homeTeam' => $data[4],
            'awayTeam' => $data[5],
            'date' => $date,
            'homeTeamScore' => '0',
            'awayTeamScore' => '0'
        ]);

    }

}
