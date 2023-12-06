<?php

namespace App\Http\Controllers\API\V0_1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required_without:email'],
            'email' => ['required_without:username', 'exclude_with:username'],
            'password' => ['required'],
        ]);

        if (!$token = auth('api')->attempt($validated)) {
            return response()->json(['error' => 'Username atau password salah'], 401);
        }

        return $this->respondWithToken(auth('api')->user(), $token);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Berhasil logout']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $refresh = auth('api')->refresh();
        return $this->respondWithToken(auth('api')->user(), $refresh);
    }

    function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required'],
            'username' => ['required', 'unique:users,username'],
            // 'photo' => ['image'],
            'email' => ['required', 'unique:users,email'],
            'password' => ['required'],
        ]);

        // $photo_url = null;
        // if ($request->hasFile('photo')) {
        //     dd($request->file('photo')->store('profiles'));

        //     $photo_url = '';
        // }

        $user = new User;
        $user->name      = $validated['name'];
        $user->username  = $validated['username'];
        $user->photo_url = null;
        $user->email     = $validated['email'];
        $user->password  = $validated['password'];
        $user->save();


        $token = auth('api')->login($user);

        return $this->respondWithToken($user, $token);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken(User $user, $token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'name' => $user->name,
                'photo_url' => $user->photo_url,
            ]
        ]);
    }
}
