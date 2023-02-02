<?php

namespace App\Http\Controllers;
use app\Models\Article;
use app\Models\User;
use app\Models\Categorie;

use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function getArticle(){
        return response()->json(Article::orderByDesc('id')->get(),200);
    }

    public function getArticleById($id){
        $article=Article::find($id);
        if(is_null($article)){
            return response()->json(['message'=>'Article non trouvé'],404);
        }
        return response()->json($article,200);
    }

    public function getArticleByVendeurId($id){
        $article=User::Where('role','vendeurId'and'articleId',$id)->get();
        return article()->json($article,200);
    }

    public function getArticleByCategorie($id){
        $article=Categorie::where('articleId',$id)->get;
        if(is_null($article)){
            return response()->json(['message'=>'Article non trouvé'],404);
        }
        return response()->json($article,200);
    }

    public function addArticle(Request $request){
        $article = Article::create($request->all());

        return response($article,200);
     }

    public function updateArticle(Request $request,$id){
        $article=Article::find($id);
        if(is_null($article)){
            return response()->json(['message'=>'Categorie non trouvé'],404);
        }
         $article->update($request->all());

        return response($article,200);
    }

    public function deleteArticle(Request $request){
        $article=Article::find($id);
        if(is_null($article)){
            return response()->json(['message'=>'Article non trouvé'],404);
        }
        $article -> delete();
        return response()->json(null,204);
    }
}
