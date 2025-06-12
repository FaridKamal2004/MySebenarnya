<?php

namespace App\Models;

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
