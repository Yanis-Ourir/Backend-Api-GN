<?php

namespace App\Repositories\Interface;

interface RepositoryInterface
{
    public function findById(int | string $id);
    public function findAll();
}
