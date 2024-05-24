<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_client', 
        'id_couturier', 
        'image_modele', 
        'couleur_tissu', 
        'date_realisation', 
        'date_commande',
        'description', 
        'id_taille'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'id_client');
    }

    public function couturier()
    {
        return $this->belongsTo(Couturier::class, 'id_couturier');
    }

    public function taille()
    {
        return $this->belongsTo(Taille::class, 'id_taille');
    }
}

