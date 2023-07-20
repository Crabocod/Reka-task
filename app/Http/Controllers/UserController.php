<?php namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function logout(){
        Auth::logout();
        return redirect()->route('index');
    }

    public function login(Request $request){
        if (Auth::attempt([
            'login' => $request->login,
            'password' => $request->pass,
        ]))
            return 1;
        else
            return 0;
    }

    public function signUp(Request $request){

        if ($request->pass == $request->pass_repeat){
            User::create([
               'login' => $request->login,
               'password' => Hash::make($request->pass),
            ]);

            Auth::attempt([
                'login' => $request->login,
                'password' => $request->pass,
            ]);
            return 1;
        }else{
            return 0;
        }

    }
}
