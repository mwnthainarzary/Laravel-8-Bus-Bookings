<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Http\Requests\BookRideRequest;
use App\Ride;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RideController extends Controller
{
    public function index()
    {
        $ridesDates = Ride::where('departure_time', '>', now())
            ->where('is_booking_open', 1)
            ->orderBy('departure_time', 'asc')
            ->get()
            ->groupBy(function ($ride) {
                return Carbon::parse($ride->departure_time)->format('Y-m-d');
            });

        return view('front.rides', compact('ridesDates'));
    }

    public function book(BookRideRequest $request)
    {
        // dd($request);
        $data = array_merge($request->validated(), ['status' => 'processing']);
        $ride = Ride::with('bus')
            ->withCount('confirmedBookings as bookings_count')
            ->find($request->input('ride_id'));

        // dd(!optional($ride)->bus, !$ride->is_booking_open, $ride->bus->places_available <= $ride->bookings_count,now()->greaterThanOrEqualTo($ride->depart_time));
        if (
            !optional($ride)->bus ||
            !$ride->is_booking_open ||
            $ride->bus->places_available <= $ride->bookings_count ||
            now()->greaterThanOrEqualTo($ride->depart_time)
        ) {
            return redirect()->back()->withErrors(['alert' => 'This ride is no longer available']);
        }

        $ride = Ride::find($request->ride_id);
        $bus_id = $ride->bus->id;
 
        DB::transaction(function()use($request, $ride, $bus_id){
            $booking = Booking::create([
                 'ticket_no' => $this->ticket(),
                 'name' => $request->name,
                 'email' => $request->email,
                 'phone' => $request->phone,
                 'status' => 'processing',
                 'bus_id' => $bus_id,
                 'ride_id' => $ride->id
             ]);
         });

        return redirect()->back()->withStatus('The ride has been successfully booked and is currently being processed');
    }

    public function ticket()
    {
        $book = Booking::orderBy('created_at', 'desc')->first();

        if(is_null($book)){
            return '100001';
        }else{
            $no = intval($book->ticket_no) + 1;

            return $no;
        }
    }
}
