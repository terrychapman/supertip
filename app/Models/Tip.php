<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Game;

class Tip extends Model
{
    use HasFactory;

    protected $fillable =  ['round', 'year', 'userId', 'teamTipped', 'points', 'superceded', 'powerTip'];


    public static function getFirstPowertip($userId) {

        $year = Game::getYear();
        $sql = DB::table('tips')
                ->select('id','round','teamTipped','powerTip')
                ->where(['userId' => $userId, 'powerTip' => 1, 'superceded' => 0, 'year' => $year[0]->year])
                ->where('round' ,'<=', '13')
                ->get();

        return $sql;
    }

    public static function get2ndPowertip($userId) {

        $year = Game::getYear();
        $sql = DB::table('tips')
                ->select('id','round','teamTipped','powerTip')
                ->where(['userId' => $userId, 'powerTip' => true, 'superceded' => false, 'year' => $year[0]->year])
                ->where('round' ,'>', 13)
                ->get();

        return $sql;
    }

    public static function getNoTipTeams($currentRoundGames, $userId) {

        $noTipTeams = ['team1' => '', 'team2' => ''];
        $lastRound = 0;

        if (count($currentRoundGames) > 0) {
            if ($currentRoundGames[0]->round > 1) {

                // get last round tip
                $lastRound = $currentRoundGames[0]->round - 1;
                $noTipTeams['team1'] = Tip::getTeamTipped($lastRound, $userId);

                // if there was a tip - see who they played last round
                if ($noTipTeams['team1'] != '') {
                    $teamPlayed = Game::getOpposition($lastRound, $noTipTeams['team1']);

                    // see who is playing them this round
                    $noTipTeams['team2'] = Game::getOpposition($currentRoundGames[0]->round, $teamPlayed);
                }
            }
        }
        return $noTipTeams;
    }

    public static function getTeamTipped($round, $userId) {

        $year = Game::getYear();
        $sql = DB::table('tips')
                ->select('teamTipped')
                ->where(['userId' => $userId, 'superceded' => false, 'year' => $year[0]->year, 'round' => $round])
                ->get();

        if (count($sql) == 0) {
            return '';
        } else {
            return $sql[0]->teamTipped;
        }
    }

    public static function getTipPoints($round, $userId) {

        $year = Game::getYear();
        $sql = DB::table('tips')
                ->select('points')
                ->where(['userId' => $userId, 'superceded' => false, 'year' => $year[0]->year, 'round' => $round])
                ->get();

        if (count($sql) == 0) {
            return 0;
        } else {
            return $sql[0]->points;
        }
    }

    public static function getTipsCountThisRound($round) {
        
        $year = Game::getYear();
        $sql = DB::select(('select teamTipped, count(*) as NoTips
                            FROM tips
                            WHERE round = ? and year = ? and superceded = 0
                            GROUP BY teamTipped'
                            ),
                            [$round,$year[0]->year]
                            );
        return $sql;
    }

    public static function getRoundTips($round) {

        $year = Game::getYear();
        $sql = DB::table('tips')
                ->select('id','userId','round','teamTipped','powerTip')
                ->where(['superceded' => false, 'year' => $year[0]->year, 'round' => $round])
                ->get();
        
        return $sql;
    }

    public static function getAllTipsUser($userId) {

        $year = Game::getYear();
        $sql = DB::table('tips')
                ->select('id','year','userId','round','teamTipped','points','powerTip')
                ->where(['superceded' => false, 'year' => $year[0]->year, 'userId' => $userId])
                ->orderby('round')
                ->get();
        
        return $sql;
    }

    public static function getEmptyTipsForRound($round) {

        $year = Game::getYear();
        $sql = DB::table('tips')
                ->select('id','userId','round','points')
                ->where(['superceded' => false, 'year' => $year[0]->year, 'round' => $round, 'teamTipped' => ''])
                ->get();

        return $sql;
    }

    public static function getAllUserPowerTips() {
        
        $year = Game::getYear();
        $sql = DB::select(('select userId, round, powerTip
                            FROM tips
                            WHERE year = ? and superceded = 0 and powerTip = 1
                            ORDER BY round'
                            ),
                            [$year[0]->year]
                            );
        return $sql;
    }
}
