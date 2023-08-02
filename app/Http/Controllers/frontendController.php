<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\messageSent;
use Illuminate\Support\Facades\Event;


class frontendController extends Controller
{
    //
    public function showRegister(){
        return view('register');
    }

    public function showSignin(){
        return view('login');
    }
    public function showDashboard(){

        return view('dashboard');
    }
    
}
