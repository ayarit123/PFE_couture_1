<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller
{
  public function register(Request $request)
  {
    $validator = Validator::make($request->all(),[
        'genre'=>'required',
        'sexe'=>'required',
        'nom'=>'required|max:50',
        'prenom'=>'required',
        'ville'=>'required',
        'adresse'=>'required',
        'email'=>'required|email|unique:users,email',
        'password'=>'required|min:8',
    ]);
    if($validator->fails())
    {
        return response()->json([
            'validation_errors'=>$validator->messages(),
        ]);
    }
    else
    {
      $user= User::create([
        'genre'=>$request->genre,
        'sexe'=>$request->sexe,
        'nom'=>$request->nom,
        'prenom'=>$request->prenom,
        'ville'=>$request->ville,
        'adresse'=>$request->adresse,
        'email'=>$request->email,
        'password'=>$request->Hash::make($request->password),
      ]);
      $token=$user->createToken($user->email.'_token')->plainTextToken;
      return response()->json([
        'status'=>200,
        'username'=>$user->nom,
        'token'=>$token,
        'message'=>'enregistrement valideé',
    ]);
    }
  }
}
