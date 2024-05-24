<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Utilisateur extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'genre', 'sexe', 'nom', 'prenom', 'ville', 'adresse', 'email', 'password', 'photo_profil'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function couturier()
    {
        return $this->hasOne(Couturier::class, 'utilisateur_id');
    }

    public function client()
    {
        return $this->hasOne(Client::class, 'utilisateur_id');
    }
}



