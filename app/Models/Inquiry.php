<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category_id',
        'status',
        'attachment_path',
    ];

    /**
     * Get the user that owns the inquiry.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category that owns the inquiry.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the assignments for the inquiry.
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * Get the status updates for the inquiry.
     */
    public function statusUpdates()
    {
        return $this->hasMany(StatusUpdate::class);
    }

    /**
     * Get the latest assignment for the inquiry.
     */
    public function latestAssignment()
    {
        return $this->hasOne(Assignment::class)->latest();
    }

    /**
     * Get the latest status update for the inquiry.
     */
    public function latestStatusUpdate()
    {
        return $this->hasOne(StatusUpdate::class)->latest();
    }
}
