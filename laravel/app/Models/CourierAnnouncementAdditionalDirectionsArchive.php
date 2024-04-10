<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourierAnnouncementAdditionalDirectionsArchive extends Model
{
    use HasFactory;
    protected $table = 'courier_announcement_additional_directions_archive';

    public function __construct(array $attributes = []) {
        $this->addColumnsFromJson();
        parent::__construct($attributes);
    }

    public function addColumnsFromJson() {
        $this->fillable = [
            'courier_announcement_id'
        ];
        $json = app(\App\Http\Controllers\JsonParserController::class)->directionsAction();
        foreach ($json as $dir) {
            $this->fillable[] = $dir[ 'name' ];
        }
    }

    public function announcementId() {
        return $this->belongsTo( CourierAnnouncementArchive::class, 'courier_announcement_id' );
    }