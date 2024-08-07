<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

class UserRepository extends Repository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * @OA\Get(
     *     path="/api/user/{pseudo}",
     *     tags={"auth"},
     *     summary="Get user by pseudo",
     *     description="Return a user by pseudo",
     *     @OA\Parameter(
     *     name="pseudo",
     *     in="path",
     *     description="Pseudo of user",
     *     required=true,
     *     @OA\Schema(
     *     type="string"
     *    )
     *  ),
     *     @OA\Response(
     *     response=200,
     *     description="successful operation"
     * ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     *  )
     */

    public function findByName($pseudo): array
    {
        $user = $this->model->where('pseudo', $pseudo)->first();

        if (!$user) {
            return ["error" => "User not found"];
        }

        return $user->toArray();
    }

    // Méthode pour register un utilisateur
    /**
     * @OA\Post (
     *     path="/api/register",
     *     tags={"auth"},
     *     summary="Register",
     *     description="Register a new user",
     *     @OA\RequestBody(
     *         description="User registration details",
     *         required=true,
     *         @OA\JsonContent(
     *             required={"pseudo", "email", "password"},
     *             @OA\Property(property="pseudo", type="string", format="text", example="user123"),
     *             @OA\Property(property="email", type="string", format="email", example="user123@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully registered"
     *     ),
     *     @OA\Response(response=400, description="Invalid input"),
     * )
     */
    public function create(array $data): array
    {

        $rules = [
            'pseudo' => 'required|min:3|max:25',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|max:25',
        ];

        $messages = $this->errorMessage();

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }


        try {
            $user = $this->model->create([
                'pseudo' => $data['pseudo'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);

            $user->save();
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }

        return $user->toArray();

    }

    public function errorMessage(): array
    {
        return [
            'email.required' => 'Email is required',
            'email.email' => 'Email is invalid',
            'email.unique' => 'Email already exists',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 6 characters',
            'password.max' => 'Password must be at most 25 characters',
            'pseudo.required' => 'Pseudo is required',
            'pseudo.min' => 'Pseudo must be at least 3 characters',
            'pseudo.max' => 'Pseudo must be at most 25 characters',
        ];
    }

}
