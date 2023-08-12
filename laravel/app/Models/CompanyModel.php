<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyModel extends Model
{
    use HasFactory;
    protected $table = 'company';
    protected $fillable = [
        'author', 'company_name', 'company_address', 'company_post_code', 'company_city',
        'company_country', 'company_phone_number', 'company_register_link', 'confirmed'
    ];

    public function authorUser() {
        return $this->belongsTo( User::class, 'author' );
    }

    // public function parcelAnnouncement() {
    //     return $this->hasMany( ParcelAnnouncement::class, 'announcement_id' );
    // }
}
