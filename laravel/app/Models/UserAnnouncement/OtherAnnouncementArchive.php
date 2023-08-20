<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherAnnouncementArchive extends Model
{
    use HasFactory;
    protected $table = 'others_parameters_archive';
    protected $fillable = [
        'announcement_id', 'description'
    ];
    public function announcementId() {
        return $this->belongsTo( UserAnnouncementArchive::class, 'announcement_id' );
    }
}
