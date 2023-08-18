<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostCodePl extends Model
{
    use HasFactory;
    protected $table = 'post_codes_pl';

    public function __construct(array $attributes = []) {
        parent::__construct($attributes);
        $this->addColumnsFromJson();
    }

    public function addColumnsFromJson() {
        $this->fillable = [
            'author'
        ];
        $json = app(\App\Http\Controllers\JsonParserController::class)->plPostCodeAction();
        $all_postcodes = json_decode($json, true);
        $this->fillable = array_merge($this->fillable, array_keys( $all_postcodes ) );
    }

    public function authorUser() {
        return $this->belongsTo( User::class, 'author' );
    }
}