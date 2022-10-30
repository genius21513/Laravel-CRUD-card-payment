<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class EditController extends Controller
{
    public function index()
    {
        return view('auth.edit');
    }

    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'o_password' => 'required|string|min:8',
            // 'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        
        $user = Auth::user();

        if (! Hash::check($request->o_password, $user->password)) {
            return back()->with('message', 'Old Password incorrect.');
        }

        // $user->password = Hash::make($request['password']);
        $user->save();
        // return redirect(route('account.home'))->with('message', 'Profil zaktualizowany');
        return back()->with('message','Profil zaktualizowany.');
    }
}
