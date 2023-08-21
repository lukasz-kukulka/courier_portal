<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HumanAnnouncementArchive extends Model
{
    use HasFactory;
    protected $table = 'humans_parameters_archive';
    protected $fillable = [
        'announcement_id', 'adult', 'kids'
    ];
    public function announcementId() {
        return $this->belongsTo( UserAnnouncementArchive::class, 'announcement_id' );
    }
}
