<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'agency_id',
    ];
    
    /**
     * Custom implementation of hasRole to ensure compatibility
     * This is a fallback in case the HasRoles trait method is not working
     *
     * @param string|array $roles
     * @return bool
     */
    public function hasRole($roles): bool
    {
        try {
            // First try to use the trait's method if it exists
            if (method_exists(HasRoles::class, 'hasRole')) {
                try {
                    // Use parent implementation from the trait
                    return parent::hasRole($roles);
                } catch (\Exception $e) {
                    // If parent method fails, continue with our custom implementation
                    \Log::warning("Parent hasRole method failed: " . $e->getMessage());
                }
            }
            
            // Fallback to our custom implementation
            $userRoles = $this->getRoleNames();
            
            if (is_string($roles) && $userRoles->contains($roles)) {
                return true;
            }
            
            if (is_array($roles)) {
                foreach ($roles as $role) {
                    if ($userRoles->contains($role)) {
                        return true;
                    }
                }
            }
            
            return false;
        } catch (\Exception $e) {
            // Log the error and return false as a safe default
            \Log::error("Error in hasRole method: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Custom implementation of getRoleNames to ensure compatibility
     * This is a fallback in case the HasRoles trait method is not working
     *
     * @return \Illuminate\Support\Collection
     */
    public function getRoleNames()
    {
        try {
            // First try to use the trait's method if it exists
            if (method_exists(HasRoles::class, 'getRoleNames')) {
                try {
                    // Use parent implementation from the trait
                    return parent::getRoleNames();
                } catch (\Exception $e) {
                    // If parent method fails, log and continue with our custom implementation
                    \Log::warning("Parent getRoleNames method failed: " . $e->getMessage());
                }
            }
            
            // Check if the necessary tables exist before querying
            if (Schema::hasTable('roles') && Schema::hasTable('model_has_roles')) {
                try {
                    // Fallback to our custom implementation
                    return DB::table('roles')
                        ->join('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
                        ->where('model_has_roles.model_id', $this->id)
                        ->where('model_has_roles.model_type', get_class($this))
                        ->pluck('roles.name');
                } catch (\Exception $e) {
                    // If query fails, log the error
                    \Log::error("Custom getRoleNames query failed: " . $e->getMessage());
                }
            } else {
                \Log::warning("Required tables for getRoleNames do not exist");
            }
            
            // If all methods fail, return an empty collection
            return collect([]);
        } catch (\Exception $e) {
            // If there's an error, log it and return an empty collection
            \Log::error("Error in getRoleNames method: " . $e->getMessage());
            return collect([]);
        }
    }

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
     * Get the agency that owns the user.
     */
    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    /**
     * Get the inquiries for the user.
     */
    public function inquiries()
    {
        return $this->hasMany(Inquiry::class);
    }

    /**
     * Get the status updates for the user.
     */
    public function statusUpdates()
    {
        return $this->hasMany(StatusUpdate::class);
    }

    /**
     * Get the notifications for the user.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
