<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'username' => $this->username,
            'country' => $this->country ? $this->country->code : null,
            'email' => $this->email,
            'email_verified' => $this->emailVerified(),
            'quota' => $this->quota,
            'gitlab_id' => $this->gitlab_id,
            'avatar' => new UserAvatarResource($this->resource),
            'subscription' => $this->getSubscriptionData(),
            'created_at' => $this->created_at,
        ];
    }
}
