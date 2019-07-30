<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Booking;
use App\Passenger;
use App\BookingPassenger;
use DB;

class BookingController extends Controller
{
    private $status = ['Unpaid', 'Paid', 'Cancelled'];

    public function index() {
        $list = Booking::with('tour')
            ->with(['booking_passenger' => function ($query) {
                $query->withCount(['passenger' => function ($query) {
                    $query->where('status', 0);
                }]);
            }])
            ->orderBy('id', 'desc')
            ->get();

        $result = [];
        foreach ($list as $key => $value) {
            $passenger_count = 0;
            foreach ($value['booking_passenger'] as $v) {
                $passenger_count += $v['passenger_count'];
            }
            $result[] = [
                'passenger' => $value['booking_passenger'],
                'id' => $value['id'],
                'tour_id' => $value['tour_id'],
                'tour_name' => $value['tour']['name'],
                'tour_date' => $value['tour_date'],
                'status' => $value['status'],
                'passenger_count' => $passenger_count,
            ];
        }

        return $result;
    }

    public function show($id) {
        $data = Booking::with(['tour.dates' => function ($query) {
                $query->where('status', 0);
            }])
            ->with('booking_passenger.passenger')
            ->find($id);

        $passengers = [];
        foreach ($data['booking_passenger'] as $key => $value) {
            $value['passenger']['special_request'] = $value['special_request'];
            $passengers[$key] = $value['passenger'];
        }

        return [
            'booking' => [
                'id' => $data['id'],
                'tour_id' => $data['tour_id'],
                'tour_name' => $data['tour']['name'],
                'tour_date' => $data['tour_date'],
                'status' => $data['status'],
                'passengers' => $passengers,
            ],
            'tour_dates' => $data['tour']['dates'],
        ];
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
                        'surname' => $value['surname'],
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

    public function update(Request $request) {
        $this->validate($request, [
            'tour_id' => 'required', 
            'tour_date' => 'required',
        ]);

        DB::beginTransaction();

        try {
            $booking = Booking::findOrFail($request->id);

            $booking->update([
                'tour_date' => $request->tour_date,
            ]);

            if ($request->passengers) {
                $passenger_ids = [];
                foreach ($request->passengers as $key => $value) {
                    $id = isset($value['id']) ? $value['id'] : 0;

                    $data = [
                        'given_name' => $value['given_name'],
                        'surname' => $value['surname'],
                        'email' => $value['email'],
                        'mobile' => $value['mobile'],
                        'passport' => $value['passport'],
                        'birth_date' => date('Y-m-d', strtotime($value['birth_date'])),
                    ];

                    $passenger = Passenger::updateOrCreate(
                        ['id' => $id], $data
                    );
                    
                    $passenger_ids[] = $passenger->id;
                    $booking->booking_passenger()->updateOrCreate(
                        ['passenger_id' => $passenger->id],
                        ['special_request' => $value['special_request']]
                    );
                }
            }

            $removed_passengers = $booking
                ->booking_passenger()
                ->select('passenger_id')
                ->whereNotIn('passenger_id', $passenger_ids)
                ->get();
            
            $removed_passenger_ids = [];
            foreach ($removed_passengers as $key => $value) {
                $removed_passenger_ids[] = $value->passenger_id;
            }

            Passenger::whereIn('id', $removed_passenger_ids)->update(['status' => 1]);
    
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