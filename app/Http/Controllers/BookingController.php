<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookingController extends Controller
{
    private $status = ['Unpaid', 'Paid', 'Cancelled'];

    public function index() {
        $tour = Tour::orderBy('id', 'desc')->get();
        foreach ($tour as $key => $value) {
            $tour[$key]['status'] = $this->status[$value['status']];
        }
        return $tour;
    }
}