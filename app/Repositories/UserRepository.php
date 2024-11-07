<?php

namespace App\Repositories;

use App\Models\Image;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

class UserRepository extends Repository
{
    private Image $modelImage;

    public function __construct(User $model, Image $modelImage)
    {
        parent::__construct($model);
        $this->modelImage = $modelImage;
    }

    public function findByUserId(int|string $id): array
    {
        $user = $this->model->where('id', $id)->first();

        if (!$user) {
            return ["error" => "User not found"];
        }

        $user['image'] = $user->image->url ?? null;

        return $user->toArray();
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

        $user['image'] = $user->image->url ?? null;

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
            'password' => [
                'required',
                'min:8',
                'max:25',
                'regex:/[0-9]/',      // password avec un chiffre
                'regex:/[-@$!%*#?&_]/', // un caractère spécial
            ],
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
        } catch (\Exception $e) {
            Log::error('Database query error: ' . $e->getMessage());
            return ["error" => $e->getMessage()];
        }

        return ["success" => "Account created successfully"];
    }

    public function update(int|string $id, array $data): array
    {
        $user = $this->model->find($id);

        if (!$user) {
            return ["error" => "User not found"];
        }

        $user->update(
            [
                'pseudo' => $data['pseudo'],
                'description' => $data['description'],
            ]
        );


        $image = $data['image'] ?? null;

        if($image !== null) {
            $imagePath = $image->store('users', 'public');
            try {
                $this->modelImage->create([
                    'name' => basename($imagePath),
                    'url' => $imagePath,
                    'imageable_type' => get_class($user),
                    'imageable_id' => $user->id,
                ]);
            } catch (QueryException $e) {
                Log::error('Database query error: ' . $e->getMessage());
                dd($e->getMessage());
            }
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
            'password.min' => 'Password must be at least 8 characters',
            'password.max' => 'Password must be at most 25 characters',
            'password.regex' => 'Password must contain at least one number, one special character',
            'pseudo.required' => 'Pseudo is required',
            'pseudo.min' => 'Pseudo must be at least 3 characters',
            'pseudo.max' => 'Pseudo must be at most 25 characters',
        ];
    }

}
