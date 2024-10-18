<?php

namespace App\Repositories;

use App\Models\Status;
use OpenApi\Annotations as OA;

class StatusRepository extends Repository
{
    public function __construct(Status $model)
    {
        parent::__construct($model);
    }

    /**
     * @OA\Post (
     *     path="/status",
     *     tags={"Status"},
     *     summary="Create a new status",
     *     description="Create a new status to indicate the game completion status",
     *     @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *     required={"name"},
     *     @OA\Property(property="name", type="string", example="Completed")
     *   )
     * ),
     *     @OA\Response(
     *     response=201,
     *     description="Status created successfully"
     *  ),
     *     @OA\Response(
     *     response=400,
     *     description="Failed to create status",
     *     @OA\JsonContent(
     *     @OA\Property(property="error", type="string", example="Invalid data provided")
     *  )
     * )
     * )
     *
     */

    public function create(array $data): array
    {
        $status = $this->model::create([
            'name' => $data['name'],
        ]);

        $status->save();

        return $status->toArray();
    }

    public function update(string | int $id, array $data): array
    {
        $status = $this->model->find($id);

        if (!$status) {
            return ['error' => 'Status not found'];
        }

        $status->update($data);

        return $status->toArray();
    }
}
