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
        'password', 'account_type', 'group', 'is_company'
    ];

    public function userAnnouncement() {
        return $this->hasMany( UserAnnouncement::class, 'author' );
    }

    public function courierAnnouncement() {
        return $this->hasMany( CourierAnnouncement::class, 'author' );
    }

    public function company() {
        return $this->hasOne( UserCompany::class, 'author' );
    }

    public function delete() {
        $this->userAnnouncement()->delete();
        $this->courierAnnouncement()->delete();
        $this->company()->delete();
        return parent::delete();
    }
}