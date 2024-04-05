<?php

namespace App\Repositories;

use App\Models\Game;
use App\Models\Platform;
use App\Repositories\Interface\RepositoryInterface;
use OpenApi\Annotations as OA;

class GameRepository extends Repository
{
    private PlatformRepository $platformRepository;
    private TagRepository $tagRepository;
    public function __construct(Game $model, PlatformRepository $platformRepository, TagRepository $tagRepository)
    {
        parent::__construct($model);
        $this->platformRepository = $platformRepository;
        $this->tagRepository = $tagRepository;
    }

    public function findByName($name): array
    {
        $game = $this->model::where('name', $name)->first();

        if (!$game) {
            return ["error" => "Game not found"];
        }

        return $game->toArray();
    }


    /**
     * @OA\Post(
     *     path="/games",
     *     tags={"games"},
     *     summary="Create a new game",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "description", "editor", "rating", "release_date", "platforms", "tags"},
     *             @OA\Property(property="name", type="string", example="The Legend of Zelda: Breath of the Wild"),
     *             @OA\Property(property="description", type="string", example="The Legend of Zelda: Breath of the Wild is an action-adventure game developed and published by Nintendo."),
     *             @OA\Property(property="editor", type="string", example="Nintendo"),
     *             @OA\Property(property="rating", type="integer", example=10),
     *             @OA\Property(property="release_date", type="string", example="2017-03-03"),
     *             @OA\Property(property="platforms", type="array", @OA\Items(type="string", example="Nintendo Switch")),
     *             @OA\Property(property="tags", type="array", @OA\Items(type="string", example="Action"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Game created successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Failed to create game",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Invalid data provided")
     *         )
     *     )
     * )
     */

    public function create(array $data): array
    {
        /**
         * @var Game $game
         */
        $game = $this->model->create([
            'name' => $data['name'],
            'description' => $data['description'],
            'editor' => $data['editor'],
            'rating' => $data['rating'],
            'release_date' => $data['release_date'],
        ]);

        $this->attachModels($game, $data['platforms'], 'platforms', $this->platformRepository);
        $this->attachModels($game, $data['tags'], 'tags', $this->tagRepository);

        $game->save();

        return $game->toArray();
    }

    private function attachModels(Game $game, array $data, string $modelName, RepositoryInterface $repository): void
    {

        /**
         * @var array[] $models
         * @var TagRepository | PlatformRepository $repository
         */
        $models = $repository->findByName($data);
        foreach ($models as $model) {
            $game->$modelName()->attach($model['id']);
        }
    }

}
