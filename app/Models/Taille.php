<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taille extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_client', 
        'longueur', 
        'longueur_manches', 
        'tour_poitrine', 
        'tour_bassin', 
        'tour_bras', 
        'longueur_ceinture'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'id_client');
    }
}

