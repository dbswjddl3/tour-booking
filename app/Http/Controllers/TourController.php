<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Tour;
use App\TourDate;

class TourController extends Controller
{
    private $status = ['Unpaid', 'Paid', 'Cancelled'];

    public function index() {
        $tour = Tour::orderBy('id', 'desc')->get();
        foreach ($tour as $key => $value) {
            $tour[$key]['status'] = $this->status[$value['status']];
        }
        return $tour;
    }

    public function show($id) {
        return Tour::with('dates')->where('id', $id)->first();
    }

    public function valid($name) {
        $count = Tour::where('name', $name)->count();
        return $count;
    }

    public function create(Request $request) {
        $this->validate($request, [
            'name' => 'required', 
            'itinerary' => 'required',
        ]);

        DB::beginTransaction();

        try {
            $tour = Tour::create([
                'name' => $request->name,
                'itinerary' => $request->itinerary,
            ]);

            if ($request->dates) {
                $dates = [];
                foreach ($request->dates as $date) {
                    $dates[] = [
                        'tour_id' => $tour->id,
                        'date'    => date('Y-m-d', strtotime($date))
                    ];
                }
    
                $tour->dates()->createMany($dates);
            }

            DB::commit();
            return response()->json([
                'status' => 'success', 
                'message' => 'Tour Saved Successfully',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error', 
                'message' => "Error: {$e->errorInfo[2]}",
            ]);
        }
    }

    public function update(Request $request) {
        $this->validate($request, [
            'name' => 'required', 
            'itinerary' => 'required',
        ]);

        DB::beginTransaction();

        try {
            $tour = Tour::findOrFail($request->id);

            $tour->update([
                'name' => $request->name,
                'itinerary' => $request->itinerary,
            ]);

            // Update old dates
            if ($request->oldDates) {
                foreach ($request->oldDates as $value) {
                    TourDate::where('id', $value['id'])->update([
                        'status' => $value['status']
                    ]);
                }
            }
    
            // Add new dates
            if ($request->dates) {
                $dates = [];
                foreach ($request->dates as $date) {
                    $dates[] = [
                        'date'    => date('Y-m-d', strtotime($date))
                    ];
                }
    
                $tour->dates()->createMany($dates);
            }

            DB::commit();
            return response()->json([
                'status' => 'success', 
                'message' => 'Tour Saved Successfully',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error', 
                'message' => "Error: {$e->errorInfo[2]}",
            ]);
        }
    }

    public function delete(Request $request, $id) {
        $tour = Tour::findOrFail($id);
        $tour->delete();

        return 204;
    }
}
