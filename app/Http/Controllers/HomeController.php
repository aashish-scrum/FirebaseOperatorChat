<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // dd(strtolower('Aashish').rand(0,100).now()->format('hisdmY'));
        return view('home');
    }
    
    public function chat()
    {
        // dd(User::withCount('messages','unread_messages')->where('id', '!=', auth('web')->id())->get()->toArray());
        return view('chat');
    }
}
