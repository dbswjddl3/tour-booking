<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Passenger extends Model
{
    public $timestamps = false;
    protected $table = 'passenger';
    protected $primaryKey = 'id';
    protected $fillable = ['given_name', 'surname', 'email', 'mobile', 'passport', 'birth_date', 'status'];
    
    public function bookings()
    {
        return $this->hasMany('App\Booking');
    }
    
    public function booking_passenger()
    {
        return $this->hasMany('App\BookingPassenger');
    }
}
