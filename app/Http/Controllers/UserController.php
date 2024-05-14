<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(UserRepository $repository)
    {
        parent::__construct($repository);
    }

    public function findByPseudo($pseudo): array
    {
        return $this->repository->findByName($pseudo);
    }

    // Méthode pour login un utilisateur
    public function login(Request $request): array
    {

        $data = $request->json()->all();

        if(auth()->attempt($data)) {
            $user = auth()->user();
            $token = $user->createToken('authToken')->plainTextToken;
            return ["user" => $user, "token" => $token];
        } else {
            return ["error" => "Information incorrect"];
        }

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
