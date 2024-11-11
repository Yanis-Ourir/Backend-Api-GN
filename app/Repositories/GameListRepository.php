<?php

namespace App\Repositories;

use App\Models\Game;
use App\Models\GameList;
use App\Models\Image;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use OpenApi\Annotations as OA;

class GameListRepository extends Repository
{
    protected Game $modelGame;
    protected Image $modelImage;
    public function __construct(GameList $model, Game $modelGame, Image $modelImage)
    {
        parent::__construct($model);
        $this->modelGame = $modelGame;
        $this->modelImage = $modelImage;
    }

    public function findById(int|string $id): array
    {
        // Eager load all necessary relationships to optimize queries
        $gameList = $this->model->with([
            'games.image',
            'games.platforms',
            'games.reviews',
            'user.image',
            'image',
            'likes',
            'dislikes'
        ])->find($id);

        // Handle case where the game list is not found
        if (!$gameList) {
            return ["error" => "Game not found"];
        }

        $gameListArray = $gameList->toArray();

        $gameListArray['games'] = $gameList->games->map(function ($game) use ($id) {
            return [
                'id' => $game->id,
                'slug' => $game->slug,
                'name' => $game->name,
                'image' => $game->image->url ?? null,
                'platforms' => $game->platforms,
                'review' => $game->reviews->where('game_list_id', $id)->map(function ($review) {
                    return [
                        'description' => $review->description,
                        'status' => $review->status,
                    ];
                })->toArray(),
            ];
        })->toArray();


        $gameListArray['image'] = $gameList->image->url ?? null;
        $gameListArray['user'] = [
            'id' => $gameList->user->id,
            'pseudo' => $gameList->user->pseudo,
            'image' => $gameList->user->image->url ?? null
        ];
        $gameListArray['likes'] = $gameList->likes->count();
        $gameListArray['dislikes'] = $gameList->dislikes->count();

        return $gameListArray;
    }



    public function findByName(string $name): array
    {
        $gameList = $this->model->where('name', $name)->first();

        if (!$gameList) {
            return ["error" => "Game list not found"];
        }

        $gameListArray = $gameList->toArray();

        $gameListArray['games'] = $gameList->games->map(function ($game) {
            return $game->name;
        })->toArray();

        $gameListArray['image'] = $gameList->image->url;

        return $gameListArray;
    }

    public function findGameListByUserId(string $userId): array
    {
        $gameLists = $this->model->where('user_id', $userId)->get();

        if ($gameLists->isEmpty()) {
            return ["error" => "Game list not found"];
        }



        return $this->sortGameListArray($gameLists);
    }


    public function checkIfGameIsAlreadyInTheList(string $userId, int $gameId): array
    {

        $gameLists = $this->model->where('user_id', $userId)->get();

        if ($gameLists->isEmpty()) {
            return ["error" => "Game list not found"];
        }

        $game = $this->modelGame->find($gameId);


        return $gameLists->map(function ($gameList) use ($game) {
            $gameListArray = $gameList->toArray();
            $gameListArray['image'] = $gameList->image ? $gameList->image->url : null;

            if($gameList->games->contains($game)) {
                $gameListArray['is_game_in_list'] = true;
            } else {
                $gameListArray['is_game_in_list'] = false;
            }
            return $gameListArray;
        })->toArray();
    }

    public function findMostLikedList(int $limit): array
    {
        $gameLists = $this->model->all();

        $gameLists = $gameLists->sortByDesc(function ($gameList) {
            return $gameList->likes->count();
        })->take($limit);


        return $this->sortGameListArray($gameLists);
    }

    private function sortGameListArray($gameLists): array
    {
        return $gameLists->map(function ($gameList) {
            $gameListArray = $gameList->toArray();
            $gameListArray['image'] = $gameList->image ? $gameList->image->url : null;
            $gameListArray['user'] = [
                'pseudo' => $gameList->user->pseudo,
                'image' => $gameList->user->image->url ?? null,
                'id' => $gameList->user->id,
            ];
            $gameListArray['likes'] = $gameList->likes->count();
            $gameListArray['dislikes'] = $gameList->dislikes->count();
            $gameListArray['games'] = $gameList->games->count();
            return $gameListArray;
        })->toArray();
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
     *     @OA\Property(property="is_private", type="boolean", example="false"),
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

        $list = $this->model->create([
            'name' => $data['name'],
            'description' => $data['description'],
            'is_private' => $data['is_private'],
            'user_id' => $data['user_id'],
        ]);

        /** @var UploadedFile $image */
        $image = $data['image'] ?? null;


        if ($image) {
            try {
                $imagePath = $image->store('game-lists', 'public');
                $this->modelImage->create([
                    'name' => basename($imagePath),
                    'url' => $imagePath,
                    'imageable_type' => get_class($list),
                    'imageable_id' => $list->id,
                ]);
            } catch (QueryException $e) {
                Log::error('Database query error: ' . $e->getMessage());
                return [
                    'error' => 'Database error while saving image',
                ];
            }
        }


        return $list->toArray();
    }

    public function addGameToList(array $data): array
    {
        $gameList = $this->model->find($data['gameListId']);
        $game = $this->modelGame->find($data['gameId']);

        if ($gameList->games->contains($game)) {
            $gameList->games()->detach($game);
            return ["error" => "Game already in list"];
        }

        $gameList->games()->attach($game);

        $gameListArray = $gameList->toArray();

        $gameListArray['games'] = $gameList->games->map(function ($game) {
            return $game->name;
        })->toArray();


        return $gameListArray;
    }

    public function removeGameFromList(array $data): array
    {
        $gameList = $this->model->find($data['game_list_id']);
        $game = $this->modelGame->find($data['game_id']);
        $gameList->games()->detach($game);

        $gameListArray = $gameList->toArray();

        $gameListArray['games'] = $gameList->games->map(function ($game) {
            return $game->name;
        })->toArray();

        return $gameListArray;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'description' => 'required|string',
            'is_private' => 'required|boolean',
            'user_id' => 'required|string|exists:' . User::class . ',id',
            'image' => 'nullable|image', 'max:2000', 'jpg,png,jpeg'
        ];
    }

    public function update(int|string $id, array $data): array
    {
        $gameList = $this->model->find($id);

        if (!$gameList) {
            return ["error" => "Game list not found"];
        }

        $gameList->update(
            [
                'name' => $data['name'],
                'description' => $data['description'],
                'is_private' => $data['is_private'],
            ]
        );

        $image = $data['image'] ?? null;

        if($image !== null) {
            $imagePath = $image->store('game-lists', 'public');
            try {
                $this->modelImage->create([
                    'name' => basename($imagePath),
                    'url' => $imagePath,
                    'imageable_type' => get_class($gameList),
                    'imageable_id' => $gameList->id,
                ]);
            } catch (QueryException $e) {
                Log::error('Database query error: ' . $e->getMessage());
                dd($e->getMessage());
            }
        }

        return $gameList->toArray();
    }
}
