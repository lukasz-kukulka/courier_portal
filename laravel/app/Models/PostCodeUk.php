<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostCodeUk extends Model
{
    use HasFactory;
    protected $table = 'post_codes_uk';

    public function __construct(array $attributes = []) {
        parent::__construct($attributes);
        $this->addColumnsFromJson();
    }

    public function addColumnsFromJson() {
        $this->fillable = [
            'courier_announcement_id'
        ];
        $json = app(\App\Http\Controllers\JsonParserController::class)->ukPostCodeAction();
        $all_postcodes = json_decode($json, true);
        $this->fillable = array_merge($this->fillable, array_keys( $all_postcodes ) );
    }

    public function authorUser() {
        return $this->belongsTo( CourierAnnouncement::class, 'courier_announcement_id' );
    }
}
