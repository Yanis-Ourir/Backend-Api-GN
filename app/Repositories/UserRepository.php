<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends Repository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByName($name): array
    {
        $user = $this->model::where('name', $name)->first();

        if (!$user) {
            return ["error" => "User not found"];
        }

        return $user->toArray();
    }

    public function create(array $data): array
    {
        $user = $this->model::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        $user->save();

        return $user->toArray();
    }
}
