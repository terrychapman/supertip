<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class ProfileController extends Controller
{
    public function index() {      
        
        $user = User::where('id', Auth::user()->id)
                    ->get();
                    
        return view('auth.profile', ['saved' => false, 'user' => $user]);
    }

    public function saveProfile() {

        //validation
        $data = request()->validate([
            'name' => 'required',
            'email' => ['required','unique:users,email,' . Auth::user()->id],
            'displayName' => ['required','unique:users,displayName,' . Auth::user()->id]
        ]);

        $user = (['id' => Auth::user()->id,
                'name' => request()->input('name'),
                'email' => request()->input('email'),
                'displayName' => request()->input('displayName')]);

        User::saveUser($user);
        $updatedUser = User::where('id', Auth::user()->id)
                            ->get();

        return view('auth.profile', ['saved' => true, 'user' => $updatedUser]);
    }
}
