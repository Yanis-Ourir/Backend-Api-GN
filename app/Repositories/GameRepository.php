<?php

namespace App\Repositories;

use App\Models\Game;
use App\Models\Platform;
use App\Repositories\Interface\RepositoryInterface;
use App\Services\AddGameInDb;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
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


    /**
     * @OA\Get(
     *     path="/game/{column}/{name}",
     *     tags={"games"},
     *     summary="Get a game by a column name and his content",
     *     @OA\Parameter(
     *         name="name",
     *         in="path",
     *         description="Name of the game",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Game details",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Game Name"),
     *             @OA\Property(property="description", type="string", example="Game Description"),
     *             @OA\Property(property="editor", type="string", example="Game Editor"),
     *             @OA\Property(property="rating", type="integer", example=9),
     *             @OA\Property(property="release_date", type="string", example="2022-01-01"),
     *             @OA\Property(property="created_at", type="string", example="2022-01-01T00:00:00.000000Z"),
     *             @OA\Property(property="updated_at", type="string", example="2022-01-01T00:00:00.000000Z"),
     *             @OA\Property(property="platforms", type="array", @OA\Items(type="string", example="Platform Name")),
     *             @OA\Property(property="tags", type="array", @OA\Items(type="string", example="Tag Name"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Game not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Game not found")
     *         )
     *     )
     * )
     */

    public function findByColumn($column, $name): array
    {
        $game = $this->model->where($column, $name)->first();

        if (!$game) {
            $api = new AddGameInDb();
            $game = $api->findGameInApi($name);
            $this->create($game);
            return $game;
        }
        $gameArray = $game->toArray();

        $gameArray['platforms'] = $game->platforms->map(function ($platform) {
            return [
                'id' => $platform->id,
                'name' => $platform->name,
                'icon' => $platform->icon,
            ];
        })->toArray();

        $gameArray['tags'] = $game->tags->map(function ($tag) {
            return $tag->name;
        })->toArray();


        return $gameArray;
    }


    public function findFirstTenMostRatedGames(): array
    {
        $games = $this->model->orderBy('rating', 'desc')->limit(10)->get();

        $gamesArray = [];

        foreach($games as $game) {
            $gameArray = $game->toArray();

            $gameArray['platforms'] = $game->platforms->map(function ($platform) {
                return [
                    'id' => $platform->id,
                    'name' => $platform->name,
                    'icon' => $platform->icon,
                ];
            })->toArray();

            $gameArray['tags'] = $game->tags->map(function ($tag) {
                return $tag->name;
            })->toArray();

            $gamesArray[] = $gameArray;
        }

        return $gamesArray;
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
        $rules = [
            'name' => 'required|string',
            'description' => 'required',
            'editor' => 'required|string',
            'platforms' => 'required|array',
            'tags' => 'required|array',
        ];


        $messages = $this->errorMessage();

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            return ['error' => $validator->errors()];
        }

        /**
         * @var Game $game
         */
        $game = $this->model->create([
            'name' => $data['name'],
            'description' => $data['description'],
            'editor' => $data['editor'],
            'rating' => $data['rating'],
            'slug' => Str::of($data['name'])->slug('-'),
            'release_date' => $data['release_date'],
        ]);


        // NEED TO ADD PLATFORMS IF DOESNT EXIST BEFORE DOING THIS =>
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

    public function delete($id): Response
    {
        $game = $this->model->find($id);

        if ($game) {
            $game->tags()->detach();
            $game->platforms()->detach();
            $game->delete();

            return response('Successfully deleted', 200);
        }

        return response('Game not found', 201);
    }

    public function errorMessage(): array
    {
        return [
            'name.required' => 'Name is required',
            'description.required' => 'Description is required',
            'editor.required' => 'Editor is required',
            'rating.integer' => 'Rating must be an integer',
            'rating.min' => 'Rating must be at least 0',
            'rating.max' => 'Rating must be at most 10',
            'release_date.required' => 'Release date is required',
            'release_date.date' => 'Release date must be a date',
            'platforms.required' => 'Platforms are required',
            'platforms.array' => 'Platforms must be an array',
            'tags.required' => 'Tags are required',
            'tags.array' => 'Tags must be an array',
        ];
    }

}
