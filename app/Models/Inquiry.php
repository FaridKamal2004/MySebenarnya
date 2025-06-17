<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;

    protected $table = 'inquiries';

    protected $fillable = [
        'InquiryId',
        'Title',
        'Description',
        'sourceURL',
        'attachment',
        'submitted_by',
        'agency_id',
        'status',
        'submitted_at',
        'updated_at',
    ];

    /**
     * Create a new inquiry.
     *
     * @param array $data
     * @return Inquiry
     */
    public static function createInquiry(array $data)
    {
        return self::create($data);
    }

    /**
     * Update the status of the inquiry.
     *
     * @param string $status
     * @return bool
     */
    public function updateStatus(string $status)
    {
        $this->status = $status;
        return $this->save();
    }

    /**
     * Assign the inquiry to an agency.
     *
     * @param int $agencyId
     * @return bool
     */
    public function assignToAgency(int $agencyId)
    {
        $this->agency_id = $agencyId;
        return $this->save();
    }

    /**
     * Get the inquiry history.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getInquiryHistory()
    {
        return $this->progress()->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get the public user that submitted this inquiry.
     */
    public function submitter()
    {
        return $this->belongsTo(PublicUser::class, 'submitted_by', 'id');
    }

    /**
     * Get the agency assigned to this inquiry.
     */
    public function agency()
    {
        return $this->belongsTo(AgencyUser::class, 'agency_id', 'id');
    }

    /**
     * Get the progress updates for this inquiry.
     */
    public function progress()
    {
        return $this->hasMany(InquiryProgress::class, 'InquiryID', 'InquiryId');
    }

    /**
     * Get the assignment for this inquiry.
     */
    public function assignment()
    {
        return $this->hasOne(Assignment::class, 'Inquiry_Id', 'InquiryId');
    }
}