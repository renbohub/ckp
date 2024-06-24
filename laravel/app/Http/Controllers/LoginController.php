<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Session;

class LoginController extends Controller
{
    public function index(Request $request){
        $data['title'] = 'Admin';
        return view('auth.login',$data);
    }

    public function post(Request $request)
    {
            $body['username'] = $request['username'];
            $body['password'] = $request['password']; 

            $response = Http::post(env('API_BASE_URL') . '/login', $body);
            if($response->status()!==200){
                return redirect()->back();
            }
            Session::put('data',json_decode($response->body()));
            return redirect('/home');
    }

    public function logout(){
        Session::flush();
        return redirect('/login');
    }
}