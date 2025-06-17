<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $table = 'assignments';

    protected $fillable = [
        'Id',
        'Inquiry_Id',
        'Agency_Id',
        'Assigned_by',
        'Assigned_at',
    ];

    protected $casts = [
        'Assigned_at' => 'datetime',
    ];

    /**
     * Create a new assignment.
     *
     * @param array $data
     * @return Assignment
     */
    public static function createAssignment(array $data)
    {
        return self::create($data);
    }

    /**
     * Get assignments by inquiry ID.
     *
     * @param int $inquiryId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAssignmentByInquiry(int $inquiryId)
    {
        return self::where('Inquiry_Id', $inquiryId)->get();
    }

    /**
     * Get the inquiry that owns this assignment.
     */
    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class, 'Inquiry_Id', 'InquiryId');
    }

    /**
     * Get the agency that owns this assignment.
     */
    public function agency()
    {
        return $this->belongsTo(AgencyUser::class, 'Agency_Id', 'id');
    }

    /**
     * Get the MCMC user that created this assignment.
     */
    public function assignedBy()
    {
        return $this->belongsTo(McmcUser::class, 'Assigned_by', 'id');
    }
}