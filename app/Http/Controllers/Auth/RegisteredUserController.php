<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request)
    {
        
        $user = User::create([
            'name' => $request->name, 
            'email' => $request->email,
            'password' => Hash::make($request->string('password')),
            'phone_number' => $request->phone_number,

        ]);
       
        $restaurant = Restaurant::create([
            'user_id' => $user->id,
            'name' => $request->restaurant_name,
            'address' => $request->location,
            'url'=>""
        ]);

        // Connexion automatique après inscription
        Auth::login($user);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Register successful',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'restaurant' => [
                'id' => $restaurant->id,
                'name' => $restaurant->name,
                'address' => $restaurant->address,
                'phone_number' => $restaurant->phone_number,
            ],
            'token' => $token,
        ], 201);
    }
}
