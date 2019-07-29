<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Booking;
use App\Passenger;
use DB;

class BookingController extends Controller
{
    private $status = ['Unpaid', 'Paid', 'Cancelled'];

    public function index() {
        return Booking::with('tour')->with('booking_passenger')->orderBy('id', 'desc')->get();
    }

    public function store(Request $request) {
        $this->validate($request, [
            'tour_id' => 'required', 
            'tour_date' => 'required',
        ]);

        DB::beginTransaction();

        try {
            $booking = Booking::create([
                'tour_id' => $request->tour_id,
                'tour_date' => $request->tour_date,
            ]);

            if ($request->passengers) {
                $data = [];
                foreach ($request->passengers as $key => $value) {
                    $passenger = Passenger::create([
                        'given_name' => $value['given_name'],
                        'sur_name' => $value['sur_name'],
                        'email' => $value['email'],
                        'mobile' => $value['mobile'],
                        'passport' => $value['passport'],
                        'birth_date' => date('Y-m-d', strtotime($value['birth_date'])),
                    ]);

                    $data[] = [
                        'booking_id' => $booking->id,
                        'passenger_id' => $passenger->id,
                        'special_request' => $value['special_request'],
                    ];
                }
                
                $booking->booking_passenger()->createMany($data);
            }
            
            DB::commit();
            return response()->json([
                'status' => 'success', 
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error', 
                'message' => "Error: {$e}",
            ]);
        }
    }
}