<?php

namespace App\Http\Resources;

use App\Activity;
use App\File;
use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{


    public $refUser;

    /**
     * @param $user
     */
    public function setRefUser(User $user){
        $this->refUser = $user;
    }

    /**
     * @param $_user
     * @return array
     */
    public static function toArrayUser( $_user ){

        $user = User::find($_user['id']);

        return [
            'id' => $_user['id'],
            'uuid' => $_user['uuid'],
            'user' => $user ? new UserBasicResource($user) : null, // Null if deleted
        ];

    }

    /**
     * @param $_file
     * @return array
     */
    public static function toArrayFile( $_file, $user = null ){

        $file = File::find($_file['id']);
        $user ??= auth()->user();

        $fileArray = null; // Will stay null if deleted
        if ($file && $user->can("view", $file )) {
            $resource = new FileResource($file);
            $resource->setRefUser($user);
            $fileArray = $resource->resolve();
        }

        return [
            'id' => $_file['id'],
            'uuid' => $_file['uuid'],
            'owner_id' => $_file['owner_id'],
            'name' => $file && $user && $user->can("view", $file) ? $file->nameForUser($user) : $_file['name'],
            'file' => $fileArray, // Null if deleted
        ];

    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $payload = null;
        $user = auth()->user() ?? $this->refUser;

        if($this->type == Activity::ACTIVITY_TYPE_GENERAL){
            $payload = [
                'text' => $this->payload['text']
            ];
        }

        else if($this->type == Activity::ACTIVITY_TYPE_CHANGE){
            $payload = [
                'file' => $this->payload['file'] ? self::toArrayFile($this->payload['file'], $user) : null,
                'changed_by' => $this->payload['changed_by'] ? self::toArrayUser($this->payload['changed_by']) : null,
            ];
        }

        else if($this->type == Activity::ACTIVITY_TYPE_SHARED){
            $payload = [
                'file' => $this->payload['file'] ? self::toArrayFile($this->payload['file'], $user) : null,
                'shared_by' => $this->payload['shared_by'] ? self::toArrayUser($this->payload['shared_by']) : null,
                'shared_to' => $this->payload['shared_to'] ? self::toArrayUser($this->payload['shared_to']) : null,
            ];
        }

        else if($this->type == Activity::ACTIVITY_TYPE_COMMENT){
            $payload = [
                'file' => $this->payload['file'] ? self::toArrayFile($this->payload['file'], $user) : null,
                'commented_by' => $this->payload['commented_by'] ? self::toArrayUser($this->payload['commented_by']) : null,
            ];
        }

        else if($this->type == Activity::ACTIVITY_TYPE_DELETE){
            $payload = [
                'file' => $this->payload['file'] ? self::toArrayFile($this->payload['file'], $user) : null,
                'deleted_by' => $this->payload['deleted_by'] ? self::toArrayUser($this->payload['deleted_by']) : null,
            ];
        }

        else if($this->type == Activity::ACTIVITY_TYPE_CREATED){
            $payload = [
                'file' => $this->payload['file'] ? self::toArrayFile($this->payload['file'], $user) : null,
                'created_by' => $this->payload['created_by'] ? self::toArrayUser($this->payload['created_by']) : null,
            ];
        }

        else if($this->type == Activity::ACTIVITY_TYPE_ASSET){
            $payload = [
                'file' => $this->payload['file'] ? self::toArrayFile($this->payload['file'], $user) : null,
                'added_by' => $this->payload['added_by'] ? self::toArrayUser($this->payload['added_by']) : null,
            ];
        }

        $file = File::find($this->linked_file);

        return [
            'id' => $this->id,
            'type' => $this->type_slug,
            'seen_at' => $this->seen_at,
            'created_at' => $this->created_at,
            'triggered_by' => $this->triggered_by ? (new UserBasicResource(User::find($this->triggered_by))) : null,
            'user_id' => $this->user_id,
            'linked_file' => $file ? (new FileResourceForActivity($file)) : null,
            'payload' => $payload,
        ];

    }
}
