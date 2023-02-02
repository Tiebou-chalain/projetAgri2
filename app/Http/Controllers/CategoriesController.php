<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Http\Controllers;
use App\Http\Controllers\CategoriesController;


use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function getCategories(){
        return response()->json(Categories::orderByDesc('id')->get(),200);
    }
    // $category=Category::getAllCategory();

    public function getCategorieById($id){
        $categorie=Categories::find($id);
        if(is_null($categorie)){
            return response()->json(['message'=>'categorie non trouvé'],404);
        }
        return response()->json($categorie,200);
    }

    public function addCategories(Request $request){
        $categorie = Categories::create($request->all());

        return response($categorie,200);
     }

    public function updateCategories(Request $request,$id){
        $categorie = Categories::find($id);
        if(is_null($categorie)){
            return response()->json(['message'=>'Categorie non trouvé'],404);
        }
         $categorie->update($request->all());

        return response($categorie,200);
    }

    public function deleteCategories(Request $request,$id){
        $categorie = Categories::find($id);
        if(is_null($categorie)){
            return response()->json(['message'=>'Categorie non trouvé'],404);
        }
        $categorie -> delete();
        return response()->json(null,204);
    }
}
