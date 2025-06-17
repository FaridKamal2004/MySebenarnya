<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class McmcUser extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'mcmc_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'MCMCUserName',
        'MCMCEmail',
        'MCMCPassword',
        'password',
        'MCMCContact',
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
        return 'MCMCEmail';
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
     * Get the role that owns the MCMC user.
     */
    public function role()
    {
        return $this->belongsTo(RoleUser::class, 'RoleID', 'RoleID');
    }

    /**
     * Get the agency users created by this MCMC user.
     */
    public function agencyUsers()
    {
        return $this->hasMany(AgencyUser::class, 'MCMCID', 'id');
    }

    /**
     * Get the inquiry progress updates made by this MCMC user.
     */
    public function inquiryProgress()
    {
        return $this->hasMany(InquiryProgress::class, 'MCMCID', 'id');
    }

    /**
     * Get the assignments created by this MCMC user.
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'Assigned_by', 'id');
    }
}