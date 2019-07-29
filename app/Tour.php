<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    public $timestamps = false;
    protected $table = 'tour';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'itinerary', 'status'];
    
    public function dates()
    {
        return $this->hasMany('App\TourDate');
    }
    
    public function bookings()
    {
        return $this->hasMany('App\Booking');
    }
}
