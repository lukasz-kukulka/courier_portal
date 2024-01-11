<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourierAnnouncement extends Model
{
    use HasFactory;
    protected $table = 'courier_announcement';
    protected $fillable = [
        'name', 'author', 'description', 'experience_date'
    ];
    public function authorUser() {
        return $this->belongsTo( User::class, 'author' );
    }

    public function cargoTypeAnnouncement() {
        return $this->hasMany( CargoTypes::class, 'courier_announcement_id' );
    }

    public function imageAnnouncement() {
        return $this->hasMany( CourierAnnouncementImages::class, 'courier_announcement_id' );
    }

    public function dateAnnouncement() {
        return $this->hasMany( CourierTravelDate::class, 'courier_announcement_id' );
    }

    public function postCodesPlAnnouncement() {
        return $this->hasMany( PostCodePl::class, 'courier_announcement_id' );
    }

    public function postCodesUkAnnouncement() {
        return $this->hasMany( PostCodeUk::class, 'courier_announcement_id' );
    }

    public function contactAnnouncement() {
        return $this->hasOne( CourierAnnouncementContact::class, 'courier_announcement_id' );
    }
}
