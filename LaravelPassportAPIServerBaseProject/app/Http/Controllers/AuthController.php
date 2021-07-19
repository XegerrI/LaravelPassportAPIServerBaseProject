<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    /**
     * Signin
     */
    public function signin(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];
        
        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken('LaravelPassportRestApiExample')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }
    
    /**
     * Signup
     */
    public function signup(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:2',
            'email' => 'required|email',
            'password' => 'required|min:4',
        ]);
        $this->handleRegistration($request);
    }
    
    final private function handleRegistration(Request $request): Response
    {
        if (!User::where('name',$request->name)->first()) {
            if (!User::where('email', $request->email)->first()) {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => bcrypt($request->password)
                ]);
                $token = $user->createToken('LaravelPassportRestApiExampl')->accessToken;
                return response()->json(['token' => $token], 200);
            } else {
                return response()->json(['error' => 'E-Mail address already registered'], 401);
            }
        } else {
            return response()->json(['error' => 'User name already taken.'], 401);
        }
    }
}
