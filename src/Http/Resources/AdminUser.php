<?php

namespace Optimus\Users\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminUser extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at
        ];
    }
}
