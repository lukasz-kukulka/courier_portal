<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleLoginData extends Model
{
    use HasFactory;

    protected $table = 'google_login_data';
    protected $fillable = [
        'user_id', 'google_id', 'google_email', 'google_name', 'google_family_name', 'email_verified'
    ];

    public function authorUser() {
        return $this->belongsTo( User::class, 'user_id' );
    }
}