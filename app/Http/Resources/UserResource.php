<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|JsonSerializable
     */
    #[ArrayShape(['data' => "array", 'links' => "array"])]
    public function toArray($request): array|JsonSerializable|Arrayable
    {
//        return parent::toArray($request);
        /** @var User|UserResource $this */
        return [
            'data' => [
                'id' => $this->id,
                'user_name' => $this->user_name,
                'email' => $this->email,
                'role_id' => $this->role_id,
                'status' => $this->status,
                'password' => $this->password,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'role' => new RoleResource($this->whenLoaded('role'))
            ],
            'links' => [
                'self' => route('rest.user', ['id' => $this->id]),
                'all' => route('rest.users'),
                'create-many' => route('rest.create-many-users')
            ]
        ];
    }
}
