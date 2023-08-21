<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PalletAnnouncementArchive extends Model
{
    use HasFactory;
    protected $table = 'pallets_parameters_archive';
    protected $fillable = [
        'announcement_id', 'weight', 'length', 'width', 'height'
    ];
    public function announcementId() {
        return $this->belongsTo( UserAnnouncementArchive::class, 'announcement_id' );
    }
}
