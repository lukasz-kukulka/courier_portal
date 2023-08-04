<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaletAnnouncement extends Model
{
    use HasFactory;
    protected $table = 'pallets_parameters';
    protected $fillable = [
        'announcement_id', 'weight', 'length', 'width', 'height'
    ];
    public function announcementId() {
        return $this->belongsTo( UserAnnouncement::class, 'announcement_id' );
    }
}
