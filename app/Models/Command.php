<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Command extends Model
{
    use HasFactory;

    protected $table = 'commande';
    protected $primaryKey = 'id';
    protected $fillable = ['acheteurId',"statut",'articleId','quantiteCommande','dateCommande','prixCommande'];


    public function acheteur()
    {
        return $this->belongsTo(User::class, 'acheteurId', 'id');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'articleId', 'id');
    }

//    "acheteurId":"1","articleId":"3"

}
