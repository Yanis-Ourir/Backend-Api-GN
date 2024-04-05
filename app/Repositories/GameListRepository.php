<?php

namespace App\Repositories;

use App\Models\GameList;
use App\Models\User;
use OpenApi\Annotations as OA;

class GameListRepository extends Repository
{
    public function __construct(GameList $model)
    {
        parent::__construct($model);
    }

    public function findByName(string $name): array
    {
        $gameList = $this->model::where('name', $name)->first();

        if (!$gameList) {
            return ["error" => "Game list not found"];
        }

        return $gameList->toArray();
    }

    /**
     * @OA\Post(
     *     path="/game-lists",
     *     tags={"GameList"},
     *     summary="Create a new game list",
     *     description="Create a new game list to store games for a user",
     *     @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *     required={"name", "description", "user_id"},
     *     @OA\Property(property="name", type="string", example="My Game List"),
     *     @OA\Property(property="description", type="string", example="A list of games I want to play"),
     *     @OA\Property(property="user_id", type="string", example="e2a1b1c1-1a1b-1c1d-1e1f-1a1b1c1d1e1f")
     *  )
     * ),
     *     @OA\Response(
     *     response=201,
     *     description="Game list created successfully"
     * ),
     *     @OA\Response(
     *     response=400,
     *     description="Failed to create game list",
     *     @OA\JsonContent(
     *     @OA\Property(property="error", type="string", example="Invalid data provided")
     * )
     * )
     *
     *
     * )
     */

    public function create(array $data): array
    {
        $like = $this->model::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'user_id' => User::find($data['user_id']),
        ]);

        $like->save();

        return $like->toArray();
    }

}
