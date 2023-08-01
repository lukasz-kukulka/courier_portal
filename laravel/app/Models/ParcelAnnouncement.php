<?php

namespace App\Models;

use App\Models\UserAnnouncement;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParcelAnnouncement extends Model
{
    use HasFactory;
    protected $table = 'parcels_parameters';
    protected $fillable = [
        'announcement_id', 'weight', 'length', 'width', 'height'
    ];
    public function announcementId() {
        return $this->belongsTo( UserAnnouncement::class, 'announcement_id' );
    }
}
