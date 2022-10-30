<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EditController extends Controller
{
    public function index()
    {
        return view('auth.edit');
    }

    public function update()
    {
        // $request->validate([
        //     'name' =>'required|min:4|string|max:255',
        //     'email'=>'required|email|string|max:255'
        // ]);
        // $user =Auth::user();
        // $user->name = $request['name'];
        // $user->email = $request['email'];
        // $user->save();
        // return back()->with('message','Profile Updated');
    }
}
