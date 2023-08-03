<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAnnouncement extends Model
{
    use HasFactory;

    protected $table = 'user_announcement_data';
    protected $fillable = [
        'author', 'direction', 'post_code_sending', 'post_code_receiving', 'phone_number', 'email',
        'expect_sending_date', 'experience_date', 'title', 'order_description_short', 'order_description_long',
        'parcels_quantity', 'humans_quantity', 'pallets_quantity', 'animals_quantity', 'others_quantity'
    ];

    public function authorUser() {
        return $this->belongsTo( User::class, 'author' );
    }

    public function parcelAnnouncement() {
        return $this->hasMany( ParcelAnnouncement::class, 'announcement_id' );
    }

    public function humanAnnouncement() {
        return $this->hasMany( HumanAnnouncement::class, 'announcement_id' );
    }

    public function palletAnnouncement() {
        return $this->hasMany( PalletAnnouncement::class, 'announcement_id' );
    }


}
