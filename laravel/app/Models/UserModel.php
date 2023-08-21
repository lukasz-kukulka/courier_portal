<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    use HasFactory;
    protected $table = 'users';
    protected $fillable = [
        'username', 'name', 'surname', 'phone_number', 'd_o_b', 'email',
        'password', 'account_type', 'group'
    ];

    public function userAnnouncement() {
        return $this->hasMany( UserAnnouncement::class, 'author' );
    }

    public function company() {
        return $this->hasOne( UserCompany::class, 'author' );
    }
}
