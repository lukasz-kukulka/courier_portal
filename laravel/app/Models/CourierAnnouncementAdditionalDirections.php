<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourierAnnouncementAdditionalDirections extends Model
{
    use HasFactory;
    protected $table = 'courier_announcement_additional_directions';

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
        // dd($this->fillable );
    }

    public function announcementId() {
        return $this->belongsTo( CourierAnnouncement::class, 'courier_announcement_id' );
    }
}