<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourierAnnouncementImagesArchive extends Model
{
    use HasFactory;
    protected $table = 'courier_announcement_images_archive';
    protected $fillable = [
        'courier_announcement_id', 'image_name', 'image_link', 'image_description'
    ];
    public function announcementId() {
        return $this->belongsTo( CourierAnnouncementArchive::class, 'courier_announcement_id' );
    }
}
