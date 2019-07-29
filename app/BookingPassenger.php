<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookingPassenger extends Model
{
    public $timestamps = false;
    protected $table = 'booking_passenger';
    protected $primaryKey = 'id';
    protected $fillable = ['booking_id', 'passenger_id', 'special_request'];

    // public function Booking() {
    //     return $this->belongsTo('App\Booking');
    // }
}
