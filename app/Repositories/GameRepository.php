<?php

namespace App\Repositories;

use App\Models\Game;
use App\Models\Image;
use App\Repositories\Interface\RepositoryInterface;
use App\Services\ExternalsApi\Interface\ExternalApiInterface;
use App\Services\GameRecommendationService;
use App\Services\RecommendationSystem\Algorithm\CollaborativeRecommendation;
use App\Services\RecommendationSystem\Algorithm\ContentRecommendation;
use App\Services\RecommendationSystem\Interface\GameRecommendation;
use App\Services\RecommendationSystem\Interface\GameRecommendationInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use OpenApi\Annotations as OA;

class GameRepository extends Repository
{
    private EvaluationRepository $evaluationRepository;
    private PlatformRepository $platformRepository;
    private TagRepository $tagRepository;
    private Image $modelImage;
    private ExternalApiInterface $api;


    public function __construct(
        Game                 $model, Image $modelImage,
        EvaluationRepository $evaluationRepository,
        PlatformRepository   $platformRepository,
        TagRepository        $tagRepository,
        ExternalApiInterface $api,
    )
    {
        parent::__construct($model);
        $this->modelImage = $modelImage;
        $this->evaluationRepository = $evaluationRepository;
        $this->platformRepository = $platformRepository;
        $this->tagRepository = $tagRepository;
        $this->api = $api;
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
            return $this->findGameInApi($name);
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

        $gameArray['image'] = $game->image ? $game->image->url : null;

        $gameArray['evaluations'] = $game->evaluations->map(function ($evaluation) {
            return [
                'id' => $evaluation->id,
                'rating' => $evaluation->rating,
                'description' => $evaluation->description,
                'game_time' => $evaluation->game_time,
                'status' => [
                    'name' => $evaluation->status->name,
                    'icon' => $evaluation->status->icon,
                    'color' => $evaluation->status->color,
                ],
                'platforms' => $evaluation->platforms->map(function ($platform) {
                    return [
                        'name' => $platform->name,
                        'icon' => $platform->icon,
                    ];
                })->toArray(),
                'user' => [
                    'id' => $evaluation->user->id,
                    'pseudo' => $evaluation->user->pseudo,
                    'image' => $evaluation->user->image ?? null,
                ],
            ];
        })->toArray();

        return $gameArray;
    }

    // A TESTER AVEC L'INTERFACE
    public function findGameInApi(string $name): array {
        return $this->api->findGameInApi(Str::slug($name));
    }


    public function findFirstTenMostRatedGames(): array
    {
        $games = $this->model->orderBy('rating', 'desc')->limit(10)->get();

        return $this->sortGameArray($games);
    }


    public function findByUserSearch(string $search): array
    {
        $games = $this->model->where('name', 'like', "%$search%")->get();

        if ($games->isEmpty()) {
            $game = $this->findGameInApi($search);
            return $this->create($game);
        }

        return $this->sortGameArray($games);
    }

    public function findGamesThatUserCanLike(string $id): array
    {
        $gameRecommendation = new GameRecommendationService(new CollaborativeRecommendation($this->evaluationRepository, $this), new ContentRecommendation($this->evaluationRepository, $this), );
        return $gameRecommendation->findGamesThatUserCanLike($id);
    }

    public function findGamesOfUserEvaluations(array $data): array
    {
        $gameIds = [];

        foreach($data as $game) {
            if (!empty($game['game_id'])) {
                $gameIds[] = $game['game_id'];
            } else {
                $gameIds[] = $game;
            }
        }

        $games = $this->model->whereIn('id', $gameIds)->get();

        return $this->sortGameArray($games);
    }

    public function sortGameArray(Collection | Model | array $games): array
    {
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

            $gameArray['image'] = $game->image ? $game->image->url : null;

            $gamesArray[] = $gameArray;
        }

        return $gamesArray;
    }

    public function findGameByTags(array $tags): array
    {
        $games = $this->model->whereHas('tags', function ($query) use ($tags) {
            $query->whereIn('name', $tags);
        })->inRandomOrder()->limit(3)->get();

        return $this->sortGameArray($games);
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

        $newImage = $this->modelImage->create([
            'name' => $data['name'] . '_background',
            'url' => $data['image'],
            'imageable_type' => get_class($game),
            'imageable_id' => $game->id,
        ]);

        $this->attachModels($game, $data['platforms'], 'platforms', $this->platformRepository);
        $this->attachModels($game, $data['tags'], 'tags', $this->tagRepository);

        $game->save();
        $game->load('platforms', 'tags');

        $gameArray = $game->toArray();
        $gameArray['image'] = $game->image ? $game->image->url : null;
        $gameArray['platforms'] = $game->platforms->toArray();
        $gameArray['tags'] = $game->tags->map(function ($tag) {
            return $tag->name;
        })->toArray();

        return [$gameArray];
    }

    private function attachModels(Game $game, array $data, string $modelName, RepositoryInterface $repository): void
    {

        /**
         * @var array[] $models
         * @var TagRepository | PlatformRepository $repository
         */
        try {
            $models = $repository->findByName($data);
        } catch (ModelNotFoundException $e) {
            $repository->create(['name' => $data[0]]);
            $models = $repository->findByName($data);
        }

        foreach ($models as $model) {
            $game->$modelName()->attach($model['id']);
        }
    }

    public function update(int|string $id, array $data): array
    {
        $game = $this->model->find($id);

        if (!$game) {
            return ["error" => "Game not found"];
        }

        $game->update(
            [
                'name' => $data['name'],
                'description' => $data['description'],
                'editor' => $data['editor'],
                'rating' => $data['rating'],
                'release_date' => $data['release_date'],
            ]
        );

        $image = $data['image'] ?? null;

        if($image !== null) {
            $imagePath = $image->store('games', 'public');
            try {
                $this->modelImage->create([
                    'name' => basename($imagePath),
                    'url' => $imagePath,
                    'imageable_type' => get_class($game),
                    'imageable_id' => $game->id,
                ]);
            } catch (QueryException $e) {
                Log::error('Database query error: ' . $e->getMessage());
                dd($e->getMessage());
            }
        }

        $this->attachModels($game, $data['platforms'], 'platforms', $this->platformRepository);
        $this->attachModels($game, $data['tags'], 'tags', $this->tagRepository);

        $game->save();

        return $game->toArray();
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
