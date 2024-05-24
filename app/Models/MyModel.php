<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyModel extends Model
{
    public $timestamps = false;
    protected $fillable = ['title', 'description', 'image_url', 'prix', 'type','created_by','id_categorie'];
    protected $table = 'models';
    public function createdBy()
    {
        return $this->belongsTo(Utilisateur::class, 'created_by');
    }
}
