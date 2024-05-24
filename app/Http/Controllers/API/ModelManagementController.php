<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\MyModel;
use App\Models\Utilisateur;

class ModelManagementController extends Controller
{
    public function index()
    {
        $models = MyModel::all(); // Récupérer tous les modèles depuis la base de données
        return response()->json($models); // Renvoyer les modèles en tant que réponse JSON
    }
    public function indexCategorie()
    {
        $categorie=Categorie::all();
        return response()->json($categorie);
    }
    public  function indexUtilisateur()
    {
        $utilisateurs=Utilisateur::all();
        return response()->json($utilisateurs);
    }
   

 public function store(Request $request){
    $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'prix' => 'required|numeric',
        'image_url' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'id_categorie' => 'required|exists:categories,id',
        'type' => 'required|in:traditionnel,moderne',
    ]);
    $user = Auth::user()->id;
    //dd($user);
    $model = new MyModel($validatedData);
    $model->title = $request->input('title');
    $model->created_by= $user;
    $model->description = $request->input('description');
    $model->prix = $request->input('prix');
    $model->type = $request->input('type');
    $model->id_categorie = $request->input('id_categorie');
       if ($request->hasFile('image_url')) {
        $imagePath = $request->file('image_url')->store('images', 'public');
        // Stocker le chemin d'accès de l'image dans la base de données
        $model->image_url = $imagePath;}
    // Enregistrer le modèle dans la base de données
    $model->save();

    // Rediriger avec un message de succès
    return response()->json([
        'success' => true,
        'redirect_url' => route('models.index') // Remplacez 'modeles' par le nom de votre route vers le composant React
    ]);
}}



