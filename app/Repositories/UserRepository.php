<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends Repository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByName($pseudo): array
    {
        $user = $this->model->where('pseudo', $pseudo)->first();

        if (!$user) {
            return ["error" => "User not found"];
        }

        return $user->toArray();
    }

    // Méthode pour register un utilisateur
    public function create(array $data): array
    {
        try {
        $user = $this->model->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        $user->save();

        } catch (\Exception $e) {
            return ["error" => $e];
        }

        return $user->toArray();
    }

}
