<?php

namespace App\Repositories\Interface;

interface RepositoryInterface
{
    public function findById(int | string $id);
    public function findAll();

    public function create(array $data);

    public function update(int | string $id, array $data);

    public function delete(int | string $id);
}
