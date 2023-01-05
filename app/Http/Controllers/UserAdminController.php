<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserAdminController extends Controller
{
    public function index() {      
        
        $users = User::all()->sortby('name');
        
        return view('admin.users', ['users' => $users]);
    }

    public function saveUser() {

        $u = (['id' => request()->input('id'),
                'name' => request()->input('name'),
                'email' => request()->input('email'),
                'displayName' => request()->input('displayName')]);

        User::saveUser($u);
       
        return redirect()->route('userAdmin');
    }

    public function deleteUser($id) {
        
        User::deleteUser($id);

        return redirect()->route('userAdmin');
    }
}
