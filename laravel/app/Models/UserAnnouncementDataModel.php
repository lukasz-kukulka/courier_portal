<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAnnouncementDataModel extends Model
{
    use HasFactory;

    protected $table = 'user_announcement_data';
    protected $fillable = [
        'title', 'author', 'order_description', 'direction', 'post_code_sending',
        'post_code_receiving', 'phone_number', 'email', 'expect_sending_date', 'experience_date'
    ];

    public function authorUser() {
        return $this->belongsTo( User::class, 'author' );
    }
}
