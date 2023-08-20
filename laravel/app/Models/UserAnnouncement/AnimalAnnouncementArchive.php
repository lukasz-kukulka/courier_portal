<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimalAnnouncementArchive extends Model
{
    use HasFactory;
    protected $table = 'animals_parameters_archive';
    protected $fillable = [
        'announcement_id', 'animal_type', 'weight', 'animal_description'
    ];
    public function announcementId() {
        return $this->belongsTo( UserAnnouncementArchive::class, 'announcement_id' );
    }
}
