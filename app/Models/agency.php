<?php

namespace App\Models;

<<<<<<< HEAD
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact',
        'email',
        'phone',
        'address',
    ];

    /**
     * Get the users for the agency.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the assignments for the agency.
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}
=======
use Illuminate\Database\Eloquent\Model;

class agency extends Model
{
    protected $fillable=[
        'name',
        'contact'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }        
}
>>>>>>> d86407c6485f806f82db76534c623a599cf91bb0
