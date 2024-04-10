<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CargoTypesArchive extends Model
{
    use HasFactory;
    protected $table = 'cargo_types_archive';
    protected $fillable = [
        'courier_announcement_id', 'cargo_name', 'cargo_price', 'cargo_description', 'currency'
    ];
    public function announcementId() {
        return $this->belongsTo( CourierAnnouncementArchive::class, 'courier_announcement_id' );
    }
}