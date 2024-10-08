<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(){
        return view('admin.dashboard');
        // $admin= Auth::guard('admins')->user();
        // return view('admin.message',['admin'=>$admin]);
    }
    public function logout(){
        Auth::guard('admins')->logout();
        return redirect()->route('admin.login');
    }
}
