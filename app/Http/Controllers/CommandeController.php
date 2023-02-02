<?php

namespace App\Http\Controllers;
use app\Models\Command;

use Illuminate\Http\Request;

class CommandeController extends Controller
{
    public function getCommandes(){
        $commandes = Command::with('acheteur')->with('article')->get();

        return response()->json($commandes,200);
    }

    public function getCommandeByArticleId($id){
        $commande = Command::where('articleId',$id)->with('acheteur')->with('article')->get();

        return response()->json($commande,200);
    }

    public function getCommandeByAcheteurId($id){
        $commande = Command:: where('acheteurId',$id)->get();
        return response()->json($commande,200);
    }

    public function getListCommandeByAcheteurId($id){
        $commandes = Command::where('acheteurId',$acheteurId);

        return response()->json($commandes,200);
    }

    public function saveCommand(Request $request){
        $commande = Command:: create([
            'acheteurId'=>$request->acheteurId,
            'articleId'=>$request->articleId,
        ]);
        return response()->json($commande,200);
    }

    public function updateCommande(Request $request,$id_commande){
        $commande = Command::find($id_commande);
        if(is_null($commande)){
            return response()->json(['message'=>'commande non trouvée']);
        }
        $commande->update($request->all());
        return response($commande,200);
    }

    public function deleteCommande($id){
        $commande = Command::find($id);
        if(is_null($commande)){
            return response()->json(['message'=>'Commande non trouvée']);
        }
        $commande->delete();
        return response(null,204);
    }
}
