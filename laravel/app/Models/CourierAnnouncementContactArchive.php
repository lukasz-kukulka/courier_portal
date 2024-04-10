<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourierAnnouncementContactArchive extends Model
{
    use HasFactory;

    protected $table = 'courier_announcement_contact_archive';
    protected $fillable = [
        'courier_announcement_id', 'name', 'surname', 'company', 'street', 'city', 'post_code', 'country', 'telephone_number', 'additional_telephone_number', 'email', 'website'
    ];
    public function announcementId() {
        return $this->belongsTo( CourierAnnouncementArchive::class, 'courier_announcement_id' );
    }
}
