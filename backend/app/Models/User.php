<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function meta(){
        return $this->hasMany(UserMeta::class, 'user_id');
    }

    public function getPreferenceAttribute(){
        $result = $this->meta()
        ->where('user_id', $this->id)
        ->first();

    if ($result) {
        return $result->meta_value;
    }

    return '{}';
    }

    public function getPreferredAuthorIdsAttribute(){
        return (array) Arr::get(json_decode($this->preference, true), 'authors');
    }

    public function getPreferredSourceIdsAttribute(){
        return (array) Arr::get(json_decode($this->preference, true), 'sources');
    }

}
