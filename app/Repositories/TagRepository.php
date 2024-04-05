<?php

namespace App\Repositories;

use App\Models\Tag;
use OpenApi\Annotations as OA;

class TagRepository extends Repository
{
    public function __construct(Tag $model)
    {
        parent::__construct($model);
    }

    public function findByName(array $names): array
    {
        $tags = $this->model->whereIn('name', $names)->get();

        if ($tags->isEmpty()) {
            return ["error" => "Tag not found"];
        }

        return $tags->toArray();
    }

    /**
     * @OA\Post (
     *     path="/tags",
     *     tags={"tags"},
     *     summary="Create a new tag",
     *     description="Create a new tag",
     *     @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *     required={"name"},
     *     @OA\Property(property="name", type="string", example="RPG")
     *    )
     *  ),
     *     @OA\Response(
     *     response=201,
     *     description="Tag created successfully"
     *   ),
     *     @OA\Response(
     *     response=400,
     *     description="Failed to create tag",
     *     @OA\JsonContent(
     *     @OA\Property(property="error", type="string", example="Invalid data provided")
     *   )
     *  )
     * )
     */

    public function create(array $data): array
    {
        $tag = $this->model::create([
            'name' => $data['name'],
        ]);

        $tag->save();

        return $tag->toArray();
    }

}
