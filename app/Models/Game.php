<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Game extends Model
{
    use HasFactory;

    protected $fillable =  ['round', 'year', 'date', 'homeTeam', 'awayTeam', 'homeTeamScore', 'awayTeamScore'];

    public static function getYear() {
        $year = DB::select('select year from games order by year desc limit 1');

        return $year;
    }

    public static function getRound($round) {
        
        $year = Game::getYear();
        $sql = DB::table('games')
                ->select('id','round','homeTeam','awayTeam','date',DB::raw('date_format(date, "%W %D %M %h:%i%p") as formattedDate'),'homeTeamScore','awayTeamScore','year')
                ->where(['round' => $round, 'year' => $year[0]->year])
                ->orderby('date')
                ->get();

        return $sql;
    }

    public static function getAllRoundNumbers() {

        $year = Game::getYear();
        $rounds = DB::table('games')
                    ->select('round')
                    ->distinct()
                    ->where('year', $year[0]->year)
                    ->orderby('round')
                    ->get();

        return $rounds;
    }

    public static function getTeamLogos() {

        $logos = [
            'Broncos' => '/images/broncos-badge.svg',
            'Bulldogs' => '/images/bulldogs-badge.svg',
            'Cowboys' => '/images/cowboys-badge.svg',
            'Dolphins' => '/images/dolphins-badge.svg',
            'Dragons' => '/images/dragons-badge.svg',
            'Eels' => '/images/eels-badge.svg',
            'Knights' => '/images/knights-badge.svg',
            'Panthers' => '/images/panthers-badge.svg',
            'Rabbitohs' => '/images/rabbitohs-badge.svg',
            'Raiders' => '/images/raiders-badge.svg',
            'Roosters' => '/images/roosters-badge.svg',
            'Sea Eagles' => '/images/sea-eagles-badge.svg',
            'Sharks' => '/images/sharks-badge.svg',
            'Storm' => '/images/storm-badge.svg',
            'Titans' => '/images/titans-badge.svg',
            'Warriors' => '/images/warriors-badge.svg',
            'Wests Tigers' => '/images/wests-tigers-badge.svg'
        ];
        return $logos;
    }

    public static function getNextGame() {

        $year = Game::getYear();
        $sql = DB::table('games')
                ->select('id','round','homeTeam','awayTeam','date',DB::raw('date_format(date, "%W %D %M %h:%i%p") as formattedDate'))
                ->where('date' ,'>', now()->addHour(10))
                ->where('year', $year[0]->year)
                ->orderby('date')
                ->take(1)
                ->get();

        return $sql;
    }

    public static function getOpposition($round, $team) {

        $year = Game::getYear();
        $opposition = '';
        $sql = DB::select(('select homeTeam,awayTeam
                             FROM games
                             WHERE round = ? and year = ? and (homeTeam = ? or awayTeam = ?)'
                             ),
                                [$round,$year[0]->year,$team,$team]
                             );
        
        if(count($sql) == 0) {
            // they had a no tip or - last round
            $opposition = '';
        }
        else if ($sql[0]->homeTeam == $team) {
            $opposition = $sql[0]->awayTeam;
        }
        else if ($sql[0]->awayTeam == $team) {
            $opposition = $sql[0]->homeTeam;
        }
        return $opposition;
    }

    public static function getGame($id) {

        $sql = DB::table('games')
                ->select('id','round','homeTeam','awayTeam','homeTeamScore','awayTeamScore')
                ->where('id', $id)
                ->get();

        return $sql;
    }

}
