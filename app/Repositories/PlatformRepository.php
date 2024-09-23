<?php

namespace App\Repositories;

use App\Models\Platform;
use http\Env\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use OpenApi\Annotations as OA;

class PlatformRepository extends Repository
{
    public function __construct(Platform $model)
    {
        parent::__construct($model);
    }

    public function findByName(array $names): array
    {
        $platforms = $this->model->whereIn('name', $names)->get();


        if ($platforms->isEmpty()) {
            throw new ModelNotFoundException("Platform not found");
        }

        return $platforms->toArray();
    }

    /**
     * @OA\Post(
     *     path="/platforms",
     *     tags={"platforms"},
     *     summary="Create a new platform",
     *     description="Create a new platform, such as a console or a computer",
     *     @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *     required={"name"},
     *     @OA\Property(property="name", type="string", example="Nintendo Switch")
     *   )
     * ),
     *     @OA\Response(
     *     response=201,
     *     description="Platform created successfully"
     * ),
     *     @OA\Response(
     *     response=400,
     *     description="Failed to create platform",
     *     @OA\JsonContent(
     *     @OA\Property(property="error", type="string", example="Invalid data provided")
     *      )
     *     )
     * )
     */

    public function create(array $data): array
    {
        $platform = $this->model::create([
            'name' => $data['name'],
            'icon' => 'IoGameControllerOutline'
        ]);

        $platform->save();

        return $platform->toArray();
    }

}
