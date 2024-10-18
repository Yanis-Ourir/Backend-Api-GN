<?php

namespace App\Repositories;

use App\Models\Image;
use OpenApi\Annotations as OA;

class ImageRepository extends Repository
{
    public function __construct(Image $model)
    {
        parent::__construct($model);
    }

    /**
     * @OA\Post (
     *     path="/images",
     *     tags={"Images"},
     *     summary="Create a new image",
     *     description="Create a new image",
     *     @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *     required={"name", "url", "imageable_type", "imageable_id"},
     *     @OA\Property(property="name", type="string", example="image1"),
     *     @OA\Property(property="url", type="string", example="https://www.example.com/image1.jpg"),
     *     @OA\Property(property="imageable_type", type="string", example="App\Models\Product"),
     *     @OA\Property(property="imageable_id",
     *     oneOf={
     *          @OA\Schema(type="string", example="e2a1b1c1-1a1b-1c1d-1e1f-1a1b1c1d1e1f"),
     *          @OA\Schema(type="integer", example=1)
     *      }
     *     ),
     *     )
     *    ),
     *     @OA\Response(
     *     response=201,
     *     description="Image created successfully",
     *     @OA\JsonContent(
     *     @OA\Property(property="name", type="string", example="image1"),
     *     @OA\Property(property="url", type="string", example="https://www.example.com/image1.jpg"),
     *     @OA\Property(property="imageable_type", type="string", example="App\Models\Product"),
     *     @OA\Property(property="imageable_id",
     *    oneOf={
     *     @OA\Schema(type="string", example="e2a1b1c1-1a1b-1c1d-1e1f-1a1b1c1d1e1f"),
     *     @OA\Schema(type="integer", example=1)
     *   }
     *     ),
     *     )
     *   ),
     *   @OA\Response(
     *          response=400,
     *          description="Failed to create image",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Invalid data provided")
     *          )
     *      )
     * )
     *
     *
     *
     */

    public function create(array $data): array
    {
        $image = $this->model->create([
            'name' => $data['name'],
            'url' => $data['url'],
            'imageable_type' => $data['imageable_type'],
            'imageable_id' => $data['imageable_id'],
        ]);

        $image->save();

        return $image->toArray();
    }

    public function update($id, array $data): array
    {
        $image = $this->model->find($id);

        if ($image) {
            $image->update($data);
            $image->save();
            return $image->toArray();
        }

        return ["error" => "Image not found"];
    }

}
