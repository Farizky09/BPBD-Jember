<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function login(Request $request)
    {
        $validated = [
            'email' => 'required',
            'password' => 'required|string'
        ];

        $request->validate($validated);
        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken('personal acces user')->plainTextToken;
            $response = ['user' => $user, 'token' => $token];
            if ($user->is_banned == 'temporary') {
                $response = ['message' => 'User is temporary banned', 'status' => 'banned_temporary', 'user' => $user];
                return response()->json($response, 403);
            } elseif ($user->is_banned == 'permanent') {
                $response = ['message' => 'User is permanent banned', 'status' => 'banned_permanent', 'user' => $user];
                return response()->json($response, 403);
            }
            return response()->json($response, 200);
        }
        $response = ['message' => 'invalid email or password'];
        return response()->json($response, 400);
    }


    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required',
                'phone_number' => 'required',
                'password' => 'required'
            ]);
            $validated['password'] = Hash::make($validated['password']);
            $user = User::create($validated);
            return response()->json($user, 201);
        } catch (\Illuminate\Validation\ValidationException $th) {
            return response()->json([
                'error' => true,
                'message' => $th->validator->errors()->first('email')
            ], 422);
        }
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
