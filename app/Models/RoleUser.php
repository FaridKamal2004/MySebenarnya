<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    use HasFactory;

    protected $table = 'role_users';
    
    protected $fillable = [
        'RoleID',
        'RoleName',
    ];

    /**
     * Get the public users associated with this role.
     */
    public function publicUsers()
    {
        return $this->hasMany(PublicUser::class, 'RoleID', 'RoleID');
    }

    /**
     * Get the agency users associated with this role.
     */
    public function agencyUsers()
    {
        return $this->hasMany(AgencyUser::class, 'RoleID', 'RoleID');
    }

    /**
     * Get the MCMC users associated with this role.
     */
    public function mcmcUsers()
    {
        return $this->hasMany(McmcUser::class, 'RoleID', 'RoleID');
    }
}