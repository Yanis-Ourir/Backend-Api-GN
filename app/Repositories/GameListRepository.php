<?php

namespace App\Repositories;

use App\Models\Game;
use App\Models\GameList;
use App\Models\Image;
use App\Models\User;
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
        $gameList = $this->model->find($id);
        if (!$gameList) {
            return ["error" => "Game not found"];
        }
        $gameListArray = $gameList->toArray();

        $gameListArray['games'] = $gameList->games->map(function ($game) {
            return $game->name;
        })->toArray();

        $gameListArray['image'] = $gameList->image->url;

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

        return $gameLists->map(function ($gameList) {
            $gameListArray = $gameList->toArray();
            $gameListArray['image'] = $gameList->image ? $gameList->image->url : null;
            $gameListArray['user'] = $gameList->user->pseudo;
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

        if($image !== null) {
            $imagePath = $image->store('game-lists', 'public');
            try {
                $newImage = $this->modelImage->create([
                    'name' => basename($imagePath),
                    'url' => $imagePath,
                    'imageable_type' => get_class($list),
                    'imageable_id' => $list->id,
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
                Log::error('Database query error: ' . $e->getMessage());
                dd($e->getMessage());
            }
        }


        return $list->toArray();
    }

    public function addGameToList(array $data): array
    {
        $gameList = $this->model->find($data['gameListId']);
        $game = $this->modelGame->find($data['gameId']);
        $gameList->games()->attach($game);

        $gameListArray = $gameList->toArray();

        $gameListArray['games'] = $gameList->games->map(function ($game) {
            return $game->name;
        })->toArray();

        return $gameListArray;
    }

    public function removeGameFromList(array $data): array
    {
        $gameList = $this->model->find($data['gameListId']);
        $game = $this->modelGame->find($data['gameId']);
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

}
