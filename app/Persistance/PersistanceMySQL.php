<?php

namespace App\Persistance;

use App\Persistance\Interface\PersistanceInterface;
use Illuminate\Database\Eloquent\Model;

class PersistanceMySQL implements PersistanceInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function create(array $data): array
    {
        $model = $this->model::create($data);
        return $model->toArray();
    }

    public function delete(int | string $id): string
    {
        $this->model::destroy($id);
        return response('Recipe deleted', 200);
    }

    public function deleteAll(): string
    {
        $this->model::truncate();
        return response('All recipes deleted', 200);
    }
}
