<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostCodePl extends Model
{
    use HasFactory;
    protected $table = 'post_codes_pl';

    public function __construct(array $attributes = []) {
        $this->addColumnsFromJson();
        parent::__construct($attributes);
        //dd($attributes);

    }

    public function addColumnsFromJson() {
        $this->fillable = [
            'courier_announcement_id'
        ];
        $json = app(\App\Http\Controllers\JsonParserController::class)->plPostCodeAction();
        $this->fillable = array_merge($this->fillable, array_values( $json ) );
    }

    public function announcementId() {
        return $this->belongsTo( CourierAnnouncement::class, 'courier_announcement_id' );
    }
}
