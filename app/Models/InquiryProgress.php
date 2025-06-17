<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InquiryProgress extends Model
{
    use HasFactory;

    protected $table = 'inquiry_progress';

    protected $fillable = [
        'ProgressID',
        'ProgressResult',
        'ProgressDescription',
        'ProgressEvidence',
        'ProgressReferences',
        'AgencyID',
        'InquiryID',
        'MCMCID',
    ];

    /**
     * Get the inquiry that owns this progress update.
     */
    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class, 'InquiryID', 'InquiryId');
    }

    /**
     * Get the agency that made this progress update.
     */
    public function agency()
    {
        return $this->belongsTo(AgencyUser::class, 'AgencyID', 'id');
    }

    /**
     * Get the MCMC user that made this progress update.
     */
    public function mcmcUser()
    {
        return $this->belongsTo(McmcUser::class, 'MCMCID', 'id');
    }
}