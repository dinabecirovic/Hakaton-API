<?php

namespace App;

use App\Mail\PasswordReset;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Ausi\SlugGenerator\SlugGenerator;
class User extends Authenticatable
{
    use Notifiable;

    // Avatars
    const AVATAR_SIZES = [26, 48, 150];
    const AVATAR_RATIO = [1, 2];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'permissions' => 'array',
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * @param $uuid
     * @return User
     */
    public static function findByUUID($uuid)
    {
        return self::where('uuid', $uuid)->first();
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo("\App\User", "parent_id");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany("\App\User", "parent_id");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function apiTokens()
    {
        return $this->hasMany("\App\ApiToken");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function managableApiTokens()
    {
        return $this->apiTokens()->where("type", '!=', ApiToken::TYPE_SYSTEM);
    }


    /**
     * @return mixed
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . " " . $this->last_name;
    }


    /**
     * @return ApiToken
     */
    public function generateNewApiToken()
    {
        $token = new ApiToken();
        $token->generate();
        $this->apiTokens()->save($token);

        return $token;
    }

    /**
     * @return ApiToken
     */
    public function getSystemApiToken()
    {
        return $this->apiTokens()->whereType(ApiToken::TYPE_SYSTEM)->first();
    }

    /**
     *
     */
    public function generateNewEmailVerifyToken()
    {

        $this->email_verification_token = Str::random(60);

    }




    /**
     * @param $permission
     * @return bool
     */
    public function hasPermissionTo($permission)
    {
        return in_array($permission, $this->permissions);
    }

    /**
     * @return mixed
     */
    public function avatarStorageFilename($size = null)
    {

        if ($size == 'original') {
            return $this->uuid . "_original.png";
        } else {
            return $this->uuid . "_" . $size . ".jpg";
        }

    }

    /**
     * @return mixed
     */
    public function avatarPublicPath($size = null)
    {
        return 'storage/avatars/' . $this->avatarStorageFilename($size);
    }

    /**
     * @return mixed
     */
    public function avatarStoragePath($size = null)
    {
        return 'public/avatars/' . $this->avatarStorageFilename($size);
    }

    /**
     * @return mixed
     */
    public function avatarUrl($size = null)
    {
        return $this->avatar_filename ? asset($this->avatarPublicPath($size)) . "?ts=" . md5($this->updated_at) : null;
    }


    /**
     * Cast an attribute to a native PHP type.
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    protected function castAttribute($key, $value)
    {
        if ($this->getCastType($key) == 'array' && is_null($value)) {
            return [];
        }

        return parent::castAttribute($key, $value);
    }

    /**
     * Send the password reset notification.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {

        Mail::send($this, new PasswordReset($this, $token));

    }

    /**
     * Remove existing avatar
     */
    public static function availableImageSizes($original = false)
    {

        if ($original) {

            $sizes[] = [
                'size' => 'original',
                'ratio' => 1,
                'size_name' => 'original'
            ];

        }

        foreach (User::AVATAR_SIZES as $size) {

            foreach (User::AVATAR_RATIO as $ratio) {

                $sizes[] = [
                    'size' => $size,
                    'ratio' => $ratio,
                    'size_name' => $size . "_" . $ratio . "x",
                ];

            }
        }

        return $sizes;

    }

    /**
     * Remove existing avatar
     */
    public function removeExistingAvatar()
    {

        foreach (self::availableImageSizes(true) as $size) {

            if (Storage::disk('local')->exists($this->avatarStoragePath($size['size_name']))) {
                Storage::disk('local')->delete($this->avatarStoragePath($size['size_name']));
            }

        }

        $this->avatar_filename = null;

    }

    /**
     * Add avatar
     * @param UploadedFile $file
     */
    public function addAvatar(UploadedFile $file)
    {


        // Store image in original size converted to PNG
        $image = Image::make($file);

        Storage::disk('local')->put($this->avatarStoragePath('original'), $image->stream());

        $image->backup();

        foreach (self::availableImageSizes(false) as $size) {

            $image->reset();
            $image->fit($size['size'] * $size['ratio'], $size['size'] * $size['ratio']);

            Storage::disk('local')->put($this->avatarStoragePath($size['size_name']), $image->stream('jpg', 100));

        }

        $this->avatar_filename = 1;

    }

    /**
     * @return bool
     */
    public function emailVerified()
    {

        return !$this->email_verification_token;

    }


    /**
     * Check does user has available space
     * @return bool
     */
    public function hasAvailableSpaceLeft()
    {

        $quota = $this->quota;
        $used = $this->usedSpace();

        if ($quota == -1)
            return true;

        return $used < $quota;

    }



    /**
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {

        return parent::save($options);

    }


}
