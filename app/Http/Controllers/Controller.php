<?php

namespace App\Http\Controllers;

use App\Repositories\Interface\RepositoryInterface;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

abstract class Controller
{
    protected RepositoryInterface $repository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @OA\Info(title="GameNest Api", version="1.0"),
     * @OA\Server(url="http://localhost:8000/api", description="GameNest Api Server"),
     * @OA\Get(
     *      path="/model/{id}",
     *      tags={"General request for our models"},
     *      summary="Get an item information by his id",
     *      description="Return a model data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Model id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function findById(string | int $id) : array
    {
        return $this->repository->findById($id);
    }

    /**
     * @OA\Get(
     *      path="/models",
     *      tags={"General request for our models"},
     *      summary="Get all items",
     *      description="Return all items models",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */

    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    public function create(Request $request): array
    {
        $data = $request->all();
        return $this->repository->create($data);
    }

    public function update(string | int $id, Request $request): array
    {
        $data = $request->all();
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $data['image'] = $image;
        }
        return $this->repository->update($id, $data);
    }

    /**
     * @OA\Delete(
     *      path="/model/{id}",
     *      tags={"General request for our models"},
     *      summary="Delete an item by his id",
     *      description="Delete an item by his id",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function delete(string | int $id): string
    {
        return $this->repository->delete($id);
    }

    public function deleteAll(): string
    {
        return $this->repository->deleteAll();
    }
}
