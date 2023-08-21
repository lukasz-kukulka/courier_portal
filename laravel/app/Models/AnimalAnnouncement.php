<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimalAnnouncement extends Model
{
    use HasFactory;
    protected $table = 'animals_parameters';
    protected $fillable = [
        'announcement_id', 'animal_type', 'weight', 'animal_description'
    ];
    public function announcementId() {
        return $this->belongsTo( UserAnnouncement::class, 'announcement_id' );
    }
}
