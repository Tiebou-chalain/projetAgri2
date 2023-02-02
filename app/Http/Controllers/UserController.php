<?php

namespace App\Http\Controllers;
use app\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // public function getAcheteur(){
    //     $acheteur = User::where('role','acheteur')->get();
    //     return response()->json($acheteur,200);
    // }

    public function getVendeur(){
        $vendeur = User::where('role','vendeur')->get();
        return response()->json($vendeur,200);
    }
    // public function getAdmin(){
    //     $admin = User::where('role','admin')->get();
    //     return response()->json($admin,200);
    // }

    

}
