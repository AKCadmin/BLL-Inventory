<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Menu; // Import the Menu model

class FrontController extends Controller

{
   
    public function index()
    {
        return view('welcome');
    }

   
    public function home()
    {
       
        if (Auth::check()) {

            $userRole =Auth::user()->role;
            $menus = Menu::all();


            return view('home', compact('userRole'), ['menus' => $menus]);
        }

        return redirect()->route('login');
    }

}
