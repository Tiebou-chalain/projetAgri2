<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Parametre;
use App\Models\Command;
use App\Models\Article;
use Illuminate\Http\Request;

class ParametreController extends Controller
{

    public function getParametre(){
        $parametre = Parametre::all()->first();
        return response()->json($parametre,200);
    }

    public function saveParametre(Request $request){

        if(Parametre::all()->count()<=0){
            $parametre = Parametre::create($request->all());
            return response($parametre,200);
        }else{
            $parametre = Parametre::all()->first();
            $parametre->update($request->all());
            return response($parametre,200);
        }

    }




}