<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourierAnnouncement extends Model
{
    use HasFactory;
    protected $table = 'courier_announcement';
    protected $fillable = [
        'author', 'description'
    ];
    public function authorUser() {
        return $this->belongsTo( User::class, 'author' );
    }

    public function cargoTypeAnnouncement() {
        return $this->hasMany( CargoTypes::class, 'courier_announcement_id' );
    }

    public function imageAnnouncement() {
        return $this->hasMany( CompanyAnnouncementImages::class, 'courier_announcement_id' );
    }

    public function travelAnnouncement() {
        return $this->hasMany( CourierTravelDate::class, 'courier_announcement_id' );
    }

    public function postCodesPLAnnouncement() {
        return $this->hasMany( PostCodePl::class, 'courier_announcement_id' );
    }
}
