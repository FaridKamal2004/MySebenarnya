<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusUpdate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'inquiry_id',
        'assignment_id',
        'user_id',
        'status',
        'comment',
    ];

    /**
     * Get the inquiry that owns the status update.
     */
    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class);
    }

    /**
     * Get the assignment that owns the status update.
     */
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    /**
     * Get the user that created the status update.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}