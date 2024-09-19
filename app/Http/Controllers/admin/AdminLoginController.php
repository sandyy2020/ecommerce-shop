<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function index(){
        return view('admin.login');
    }
    public function authenticate(Request $request){
        $credentials=$request->validate([
                'email'=>'required|email',
                'password'=>'required'
        ]);
        // $credentials=$request->only('email','password');
        if(Auth::guard('admins')->attempt($credentials)){
            // Retrieve the authenticated user
        $user = Auth::guard('admins')->user();

        // Check if the role is 'admin'
        if ($user->role === '2') {
            return redirect()->route('admin.dashboard'); // Redirect to admin dashboard
        } else {
            Auth::guard('admins')->logout(); // Log out if role is not 'admin'
            return redirect()->route('admin.login')->withErrors([
                'email' => 'You do not have the required permissions.',
            ]);
        }
    }
        return back()->withErrors([
            'email'=>'The provided credentials donot match our records.',
        ]);
    }
    
}
