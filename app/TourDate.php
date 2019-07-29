<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TourDate extends Model
{
    public $timestamps = false;
    protected $table = 'tour_date';
    protected $primaryKey = 'id';
    protected $fillable = ['tour_id', 'date', 'status'];
    protected $dates = ['date'];

    public function tour() {
        return $this->belongsTo('App\Tour');
    }
}
