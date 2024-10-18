<?php

namespace App\Repositories;

use App\Models\Message;
use App\Models\User;
use OpenApi\Annotations as OA;

class MessageRepository extends Repository
{
    public function __construct(Message $model)
    {
        parent::__construct($model);
    }

    /**
     * @OA\Get(
     *     path="/messages/{user_id}",
     *     tags={"messages"},
     *     summary="Find messages by user ID",
     *     description="Returns all messages sent by a user",
     *     @OA\Parameter(
     *     name="user_id",
     *     in="path",
     *     required=true,
     *     description="ID of the user",
     *     @OA\Schema(
     *     type="string",
     *     example="e2a1b1c1-1a1b-1c1d-1e1f-1a1b1c1d1e1f"
     *    )
     * ),
     *     @OA\Response(
     *     response=200,
     *     description="Messages found"
     * ),
     *     @OA\Response(
     *     response=404,
     *     description="Messages not found",
     *     @OA\JsonContent(
     *     @OA\Property(property="error", type="string", example="Messages not found")
     *     )
     * )
     * )
     */

    public function findByUserId(string $userId): array
    {
        $like = $this->model::where('user_id', $userId)->get();

        return $like->toArray();
    }

    /**
     * @OA\Post(
     *     path="/messages",
     *     tags={"messages"},
     *     summary="Create a new message",
     *     description="Create a new message to send to another user",
     *     @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *     required={"content", "user_id", "user_receiver_id"},
     *     @OA\Property(property="content", type="string", example="Hello, how are you?"),
     *     @OA\Property(property="user_id", type="string", example="e2a1b1c1-1a1b-1c1d-1e1f-1a1b1c1d1e1f"),
     *     @OA\Property(property="user_receiver_id", type="string", example="e2a1b1c1-1a1b-1c1d-1e1f-1a1b1c1d1e1f")
     *   )
     * ),
     *     @OA\Response(
     *     response=201,
     *     description="Message created successfully"
     *  ),
     *     @OA\Response(
     *     response=400,
     *     description="Failed to create message",
     *     @OA\JsonContent(
     *     @OA\Property(property="error", type="string", example="Invalid data provided")
     *      )
     *     )
     * )
     */

    public function create(array $data): array
    {
        $message = $this->model::create([
            'content' => $data['content'],
            'user_id' => User::find($data['user_id']),
            'user_receiver_id' => User::find($data['user_receiver_id']),
        ]);

        $message->save();

        return $message->toArray();
    }

    public function update(string | int $id, array $data): array
    {
        $message = $this->model->find($data['id']);

        if ($message === null) {
            return ['error' => 'Message not found'];
        }

        $message->content = $data['content'];
        $message->user_id = User::find($data['user_id']);
        $message->user_receiver_id = User::find($data['user_receiver_id']);

        $message->save();

        return $message->toArray();
    }

}
