<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'displayName',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function getUser($id) {

        $sql = DB::table('users')
                ->select('id','name','email','displayName')
                ->where('id', $id)
                ->get();

        return $sql;
    }

    public static function saveUser($u) {

        User::where('id', $u['id'])
            ->update([
                'name' => $u['name'],
                'email' => $u['email'],
                'displayName' => $u['displayName']
            ]);
    }

    public static function deleteUser($id) {

        User::where('id', $id)
            ->delete();
    }
    
}
