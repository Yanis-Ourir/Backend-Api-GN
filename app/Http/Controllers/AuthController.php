<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

class AuthController extends \Illuminate\Routing\Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * @OA\Post (
     *     path="/api/auth/login",
     *     tags={"auth"},
     *     summary="Login",
     *     description="Login a user",
     *     @OA\Parameter(
     *           name="email",
     *           in="query",
     *           description="The email of the user",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *     @OA\Parameter(
     *           name="password",
     *           in="query",
     *           description="The password of the user",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *       @OA\Response(
     *           response=200,
     *           description="successfully logged in"
     *        ),
     *       @OA\Response(response=400, description="Invalid credentials"),
     *       @OA\Response(response=404, description="Login path Not Found"),
     * )
     */
    public function login(): \Illuminate\Http\JsonResponse
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me(): \Illuminate\Http\JsonResponse
    {
        return response()->json(auth()->user());
    }

    public function logout(): \Illuminate\Http\JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh(): \Illuminate\Http\JsonResponse
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
