<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class PublicUser extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'public_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'PublicName',
        'PublicEmail',
        'PublicPassword',
        'password',
        'PublicContact',
        'PublicStatusVerify',
        'RoleID',
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
        'password' => 'hashed',
    ];
    
    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'PublicEmail';
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the role that owns the public user.
     */
    public function role()
    {
        return $this->belongsTo(RoleUser::class, 'RoleID', 'RoleID');
    }

    /**
     * Get the inquiries submitted by this public user.
     */
    public function inquiries()
    {
        return $this->hasMany(Inquiry::class, 'submitted_by', 'id');
    }
}