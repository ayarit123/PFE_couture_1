<?php


namespace App\Http\Controllers\API;
use App\Models\Taille;

use App\Models\Utilisateur;
use App\Models\Couturier;
use App\Models\Client;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
class UtilisateurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

public function store(Request $request)
{
    $validatedData = $request->validate([
        'genre' => 'required',
        'sexe' => 'required',
        'nom' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'ville' => 'required|string|max:255',
        'adresse' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:utilisateurs',
        'password' => 'required|string|min:8',
        'diplomes' => 'nullable|string|max:255', // Validation pour tailleur
        'specialite' => 'nullable|string|max:255', // Validation pour tailleur
        'competences' => 'nullable|string|max:255', // Validation pour tailleur
        'adresse_livraison' => 'nullable|string|max:255', // Validation pour client
    ]);

    $validatedData['password'] = Hash::make($validatedData['password']);
    $utilisateur = Utilisateur::create($validatedData);

    // Log pour vérifier les données
    Log::info('Utilisateur créé:', $utilisateur->toArray());

    if ($utilisateur->genre === 'tailleur') {
        Log::info('Tentative de création d\'un couturier pour l\'utilisateur ID:', ['id' => $utilisateur->id]);
        try {
            $couturier = Couturier::create([
                'utilisateur_id' => $utilisateur->id,
                'diplomes' => json_encode($request->input('diplomes', '')), // Convertir en JSON
                'specialite' => $request->input('specialite', ''),
                'competences' => $request->input('competences', ''),
            ]);
            Log::info('Couturier créé avec succès:', $couturier->toArray());
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du couturier:', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 500,
                'message' => 'Erreur lors de la création du couturier',
            ], 500);
        }
    } elseif ($utilisateur->genre === 'client') {
        Log::info('Tentative de création d\'un client pour l\'utilisateur ID:', ['id' => $utilisateur->id]);
        try {
            $client = Client::create([
                'utilisateur_id' => $utilisateur->id,
                'adresse_livraison' => $request->input('adresse_livraison', ''),
            ]);
            Log::info('Client créé avec succès:', $client->toArray());
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du client:', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 500,
                'message' => 'Erreur lors de la création du client',
            ], 500);
        }
    }

    $token = $utilisateur->createToken($utilisateur->email . '_Token')->plainTextToken;

    return response()->json([
        'status' => 201,
        'token' => $token,
        'message' => 'Enregistrement réussi',
        'utilisateur' => $utilisateur
    ], 201);
}


public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = Utilisateur::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            Log::warning('Échec de la connexion pour:', ['email' => $credentials['email']]);
            return response()->json(['message' => 'Identifiants invalides'], 401);
        }

        $token = $user->createToken('Personal Access Token')->plainTextToken;

        Log::info('Connexion réussie pour l\'utilisateur:', ['user_id' => $user->id, 'email' => $user->email]);

        return response()->json([
            'status' => 201,
            'token' => $token,
            'message' => 'Connexion réussie',
            'user' => $user
        ]);
    }


    // Other existing methods...

    public function getUserData(Request $request)
    {
        $user = $request->user();
    
        if ($user->genre === 'tailleur') {
            $tailleur = Couturier::where('utilisateur_id', $user->id)->first();
            $user->photo_profil = Storage::url($user->photo_profil); // Convertit le chemin en URL publique
    
            return response()->json([
                'nom' => $user->nom,
                'prenom' => $user->prenom,
                'email' => $user->email,
                'ville' => $user->ville,
                'adresse' => $user->adresse,
                'genre' => $user->genre,
                'diplomes' => $tailleur->diplomes,
                'specialite' => $tailleur->specialite,
                'competences' => $tailleur->competences,
                'photo_profil' => $user->photo_profil // URL complète
            ]);
        } else {
            $client = Client::where('utilisateur_id', $user->id)->first();
            $taille = Taille::where('id_client', $client->id)->first();
            $user->photo_profil = Storage::url($user->photo_profil); // Convertit le chemin en URL publique
    
            return response()->json([
                'nom' => $user->nom,
                'prenom' => $user->prenom,
                'email' => $user->email,
                'ville' => $user->ville,
                'adresse' => $user->adresse,
                'genre' => $user->genre,
                'adresse_livraison' => $client->adresse_livraison,
                'photo_profil' => $user->photo_profil, // URL complète
                'taille' => $taille // Ajout des informations de taille
            ]);
        }
    }
    




    public function uploadPhoto(Request $request)
{
    $request->validate([
        'photo_profil' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    $user = $request->user();
    if (!$user) {
        return response()->json([
            'status' => 404,
            'message' => 'Utilisateur non trouvé'
        ], 404);
    }

    $path = $request->file('photo_profil')->store('photos_profil', 'public');

    if ($user->photo_profil) {
        Storage::disk('public')->delete($user->photo_profil);
    }

    $user->photo_profil = $path;
    $user->save();  // Assurez-vous de sauvegarder l'utilisateur après avoir mis à jour le chemin de la photo

    return response()->json([
        'status' => 200,
        'message' => 'Photo de profil mise à jour avec succès',
        'photo_profil' => $path
    ]);
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
