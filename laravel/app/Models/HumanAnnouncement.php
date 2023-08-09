<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HumanAnnouncement extends Model
{
    use HasFactory;
    protected $table = 'humans_parameters';
    protected $fillable = [
        'announcement_id', 'adult', 'kids'
    ];
    public function announcementId() {
        return $this->belongsTo( UserAnnouncement::class, 'announcement_id' );
    }
}
