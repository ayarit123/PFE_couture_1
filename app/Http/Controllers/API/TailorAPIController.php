<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Couturier;
use Illuminate\Support\Facades\Storage;

class TailorAPIController extends Controller
{
    public function index()
    {
        $couturiers = Couturier::with('utilisateur')->get();

        // Ajouter l'URL complète pour les images de profil
        $couturiers->each(function ($couturier) {
            if ($couturier->utilisateur && $couturier->utilisateur->photo_profil) {
                $couturier->utilisateur->photo_profil = Storage::url($couturier->utilisateur->photo_profil);
            }
        });

        return response()->json($couturiers);
    }
}
