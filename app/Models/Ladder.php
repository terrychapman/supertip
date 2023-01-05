<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Game;

class Ladder extends Model
{
    use HasFactory;

    protected $fillable =  ['round', 'year', 'rank', 'userId', 'teamTipped', 'roundPoints', 'totalPoints', 'powerTip','paid','disabled'];

    public static function getLadderForUser($userId) {

        $year = Game::getYear();
        $sql = DB::table('ladders')
                ->select('id','year','round','userId','teamTipped','powerTip','roundPoints','totalPoints','paid','disabled')
                ->where(['userId' => $userId, 'year' => $year[0]->year])
                ->get();

        return $sql;
    }

    public static function getLadder() {

        $year = Game::getYear();
        $sql = DB::table('ladders')
            ->select('ladders.year','ladders.round','users.displayName','users.id as userId','ladders.teamTipped','ladders.powerTip','ladders.roundPoints'
                    ,'ladders.totalPoints','ladders.paid','ladders.id')
            ->join('users', 'users.id', '=', 'ladders.userId')
            ->where(['ladders.year' => $year[0]->year])
            ->orderby('ladders.totalPoints', 'desc')
            ->get();

        return $sql;
    }

    public static function saveLadder($l) {

        Ladder::where('id', $l['id'])
            ->update([
                'roundPoints' => $l['roundPoints'],
                'totalPoints' => $l['totalPoints'],
                'powerTip' => $l['powerTip'],
                'paid' => $l['paid']
            ]);
    }

    public static function getEmptyTipsForRound($round) {

        $year = Game::getYear();
        $sql = DB::table('ladders')
                ->select('id','round','userId','totalPoints','paid','disabled')
                ->where(['round' => $round, 'year' => $year[0]->year, 'teamTipped' => ''])
                ->get();

        return $sql;
    }

}
