<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourierTravelDate extends Model
{
    use HasFactory;
    protected $table = 'courier_announcement_travel_dates';
    protected $fillable = [
        'courier_announcement_id', 'dir_from', 'dir_to', 'additional_dir', 'date', 'description'
    ];
    public function announcementId() {
        return $this->belongsTo( CourierAnnouncement::class, 'courier_announcement_id' );
    }
}