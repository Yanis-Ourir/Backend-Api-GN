<?php

namespace App\Http\Controllers;

use App\Persistance\Interface\PersistanceInterface;
use App\Repository\Interface\RepositoryInterface;

abstract class Controller
{
    protected RepositoryInterface $repository;
    protected PersistanceInterface $persistance;

    public function __construct(RepositoryInterface $repository, PersistanceInterface $persistance)
    {
        $this->repository = $repository;
        $this->persistance = $persistance;
    }

    public function findById($id)
    {
        return $this->repository->findById($id);
    }

    public function findAll()
    {
        return $this->repository->findAll();
    }

    public function create($data): array
    {
        return $this->persistance->create($data);
    }

    public function delete($id): string
    {
        return $this->persistance->delete($id);
    }

    public function deleteAll(): string
    {
        return $this->persistance->deleteAll();
    }
}
