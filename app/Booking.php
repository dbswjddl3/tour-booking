<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    public $timestamps = false;
    protected $table = 'booking';
    protected $primaryKey = 'id';
    protected $fillable = ['tour_id', 'tour_date', 'status'];

    public function tour() {
        return $this->belongsTo('App\Tour');
    }

    public function booking_passenger()
    {
        return $this->hasMany('App\BookingPassenger');
    }

    public function invoice()
    {
        return $this->hasOne('App\Invoice');
    }
}
