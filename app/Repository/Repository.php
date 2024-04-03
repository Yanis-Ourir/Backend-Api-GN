<?php

namespace App\Repository;

use App\Models\User;
use App\Repository\Interface\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

abstract class Repository implements RepositoryInterface
{
    protected Model $model;

    public function __construct($model)
    {
        $this->model = $model;
    }
    public function findById(int | string $id): array
    {
        return $this->model::find($id)->toArray();
    }

    public function findAll(): array
    {
        return $this->model::all()->toArray();
    }

}
