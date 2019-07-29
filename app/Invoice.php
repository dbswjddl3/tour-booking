<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    public $timestamps = false;
    protected $table = 'invoice';
    protected $primaryKey = 'id';
    protected $fillable = ['booking_id', 'amount', 'status'];

    public function booking() {
        return $this->belongsTo('App\Booking');
    }
}
