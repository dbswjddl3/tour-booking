<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Passenger extends Model
{
    public $timestamps = false;
    protected $table = 'passenger';
    protected $primaryKey = 'id';
    protected $fillable = ['given_name', 'surname', 'email', 'mobile', 'passport', 'birth_date', 'status'];
    
    public function Bookings()
    {
        return $this->hasMany('App\Booking');
    }
}
