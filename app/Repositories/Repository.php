<?php

namespace App\Repositories;

use App\Repositories\Interface\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;


abstract class Repository implements RepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function findByCriteria(array $criteria): array
    {
        $model = $this->model->where($criteria)->get();
        if (!$model) {
            return ["error" => "Not found with these criteria"];
        }
        return $model->toArray();
    }

    public function findById(int | string $id): array
    {
        $model = $this->model->find($id);
        if (!$model) {
            return ["error" => "Not found"];
        }
        return $model->toArray();
    }

    public function findAll(): array
    {
        return $this->model::all()->toArray();
    }

    abstract public function create(array $data): array;

    // RENDRE UPDATE ABSTRACT
    abstract public function update(int | string $id, array $data): array;

    public function delete(int | string $id): Response
    {
        $this->model::destroy($id);
        return response('Successfully deleted', 200);
    }

    public function deleteAll(): string
    {
        $this->model->truncate();
        return response('Deleted all in table', 200);
    }

}
