<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Empty_;

class AuthController extends Controller
{
    public function login(){

        return view("login");

    }

    public function loginSubmit(Request $request){

        $rules = [
            "text_username"=> ["required"],
            "text_password" => ["required"]
        ];

        $feedback = [
            "text_username.required" => "O campo username não pode ser vazio",
            "text_password.required" => "O campo password não pode ser vazio"
        ];
        
        $request->validate($rules, $feedback);

        $username = $request->input("text_username");
        $password = $request->input("text_password");

        $user = User::where("username",  $username)
        ->where("deleted_at", NULL)
        ->first();

        if(!$user || $user == "" || !isset($user)){
            return redirect()->back()->withInput()->with("loginError", "Username ou password incorretos");
        }

        if(!password_verify($password, $user->password)){
            return redirect()->back()->withInput()->with("loginError", "Username ou password incorretos");
        }

        $user->last_login = date("Y-m-d H:i:s");
        $user->save();


        session([
            "user" => [
                "id" => $user->id,
                "username" => $user->username
            ]
            ]);

        //$users = User::all()->toArray();

        //$userModel = new User;
        //$users = $userModel->all()->toArray();

        return redirect()->to("/");
        

    }

    public function logout(){

        session()->forget("user");
        return redirect()->to("/login");

    }

}
