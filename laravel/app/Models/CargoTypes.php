<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CargoTypes extends Model
{
    use HasFactory;
    protected $table = 'cargo_types';
    protected $fillable = [
        'courier_announcement_id', 'cargo,name', 'cargo_price', 'cargo_description'
    ];
    public function announcementId() {
        return $this->belongsTo( CourierAnnouncement::class, 'courier_announcement_id' );
    }
}
