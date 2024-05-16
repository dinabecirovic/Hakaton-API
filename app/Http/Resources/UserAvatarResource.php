<?php

namespace App\Http\Resources;

use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAvatarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {


        $avatars = [];

        foreach (User::availableImageSizes(false) as $size){

            $avatars[ $size['size_name'] ] = $this->avatarUrl($size['size_name']);

        }

        return $avatars;
    }
}
