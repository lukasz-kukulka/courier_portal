<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostCodePlArchive extends Model
{

    protected $table = 'post_codes_pl_archive';

    public function __construct(array $attributes = []) {
        parent::__construct($attributes);
        $this->addColumnsFromJson();
    }

    public function addColumnsFromJson() {
        $this->fillable = [
            'courier_announcement_id'
        ];
        $json = app(\App\Http\Controllers\JsonParserController::class)->plPostCodeAction();
        $all_postcodes = json_decode($json, true);
        $this->fillable = array_merge($this->fillable, array_keys( $all_postcodes ) );
    }

    public function courierAnnouncement() {
        return $this->belongsTo( CourierAnnouncementArchive::class, 'courier_announcement_id' );
    }
}
