<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourierAnnouncementArchive extends Model
{
    use HasFactory;
    protected $table = 'courier_announcement_archive';
    protected $fillable = [
        'author', 'description', 'experience_date'
    ];
    public function authorUser() {
        return $this->belongsTo( User::class, 'author' );
    }

    public function cargoTypeAnnouncement() {
        return $this->hasMany( CargoTypesArchive::class, 'courier_announcement_id' );
    }

    public function imageAnnouncement() {
        return $this->hasMany( CompanyAnnouncementImagesArchive::class, 'courier_announcement_id' );
    }

    public function travelAnnouncement() {
        return $this->hasMany( CourierTravelDateArchive::class, 'courier_announcement_id' );
    }

    public function postCodesPlAnnouncement() {
        return $this->hasMany( PostCodePlArchive::class, 'courier_announcement_id' );
    }

    public function postCodesUkAnnouncement() {
        return $this->hasMany( PostCodeUkArchive::class, 'courier_announcement_id' );
    }

    public function dateAnnouncement() {
        return $this->hasMany( CourierTravelDateArchive::class, 'courier_announcement_id' );
    }
}
