<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class AgencyUser extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'agency_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'AgencyUserName',
        'AgencyEmail',
        'AgencyPassword',
        'password',
        'AgencyContact',
        'AgencyFirstLogin',
        'RoleID',
        'MCMCID',
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
        'AgencyFirstLogin' => 'boolean',
    ];
    
    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'AgencyEmail';
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
     * Get the role that owns the agency user.
     */
    public function role()
    {
        return $this->belongsTo(RoleUser::class, 'RoleID', 'RoleID');
    }

    /**
     * Get the MCMC user that created this agency user.
     */
    public function mcmcUser()
    {
        return $this->belongsTo(McmcUser::class, 'MCMCID', 'id');
    }

    /**
     * Get the assignments for this agency.
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'Agency_Id', 'id');
    }

    /**
     * Get the inquiry progress updates made by this agency.
     */
    public function inquiryProgress()
    {
        return $this->hasMany(InquiryProgress::class, 'AgencyID', 'id');
    }
}