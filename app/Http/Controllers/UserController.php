<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(5);
        return view('admin.users.index', ['users' => $users]);
    }

    public function show(User $user)
    {
        return view('admin.users.profile', [
            'user'=>$user,
            'roles'=>Role::all() 
            
        ]);
    }

    public function update(User $user)
    {
        $inputs = request()->validate([
            'username'=> ['required', 'string', 'max:255', 'alpha_dash'],
            'name'=> ['required', 'string', 'max:255'],
            'last_name'=> ['required', 'string', 'max:255'],
            'email'=> ['required', 'email', 'max:255'],
            'avatar'=> ['file']
        ]);

        if(request('avatar'))
        {
            $inputs['avatar'] = request('avatar')->store('images');
        }

        $user->update($inputs);
        session()->flash('user-updated', 'user has beed updated');
        return back();
    }

    public function attach(User $user)
    {
        $user->roles()->attach(request('role'));
        return back();
    }

    public function detach(User $user)
    {
        $user->roles()->detach(request('role'));
        return back();
    }

    public function delete(User $user)
    {
        $user->delete();
        session()->flash('user-delete', 'user has beed deleted');
        return back();
    }
}