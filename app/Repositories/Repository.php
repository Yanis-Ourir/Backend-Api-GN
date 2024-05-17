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
        return $this->model->where($criteria)->get()->toArray();
    }

    public function findById(int | string $id): array
    {
        return $this->model->find($id)->toArray();
    }

    public function findAll(): array
    {
        return $this->model::all()->toArray();
    }

    abstract public function create(array $data): array;

    // RENDRE UPDATE ABSTRACT
    public function update(int | string $id, array $data): array
    {
        $model = $this->model->find($id);
        $model->update($data);
        return $model->toArray();
    }

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
