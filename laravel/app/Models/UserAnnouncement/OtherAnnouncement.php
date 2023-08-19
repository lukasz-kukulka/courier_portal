<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherAnnouncement extends Model
{
    use HasFactory;
    protected $table = 'others_parameters';
    protected $fillable = [
        'announcement_id', 'description'
    ];
    public function announcementId() {
        return $this->belongsTo( UserAnnouncement::class, 'announcement_id' );
    }
}
