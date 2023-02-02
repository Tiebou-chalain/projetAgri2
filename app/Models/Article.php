<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $table = 'article';
    protected $primaryKey = 'id';
    protected $fillable = ['nomArticle','photoArticle','descriptionArticle','quantiteArticle','currency',"statut",'unite'];

    public function commands()
    {
        return $this->hasMany('App\Models\Command','articleId','id');
    }

    public function vendeurs()
    {
        return $this->hasMany('App\Models\User','articleId','id');
    }

    public function categories()
    {
        return $this->hasMany('App\Models\Categorie','articleId','id');
    }
}
