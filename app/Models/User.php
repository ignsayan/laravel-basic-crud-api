<?php

namespace App\Models;

use App\Modules\Authentication\Notifications\EmailVerification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\MustVerifyEmail as HasVerifiedEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements MustVerifyEmail, HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasVerifiedEmail, InteractsWithMedia;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_USER = 'user';

    public const SUPPORTED_IMAGE_MIME_TYPES = [
        'image/jpeg',
        'image/png',
    ];

    protected $fillable = [
        'uuid',
        'name',
        'email',
        'phone_no',
        'password',
        'email_verified_at',
        'phone_verified_at',
        'oauth_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function sendEmailVerificationNotification()
    {
        $this->notify(new EmailVerification($this));
    }
}
