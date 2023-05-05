<?php

namespace App\Http\Controllers;

use App\Booking;
use Illuminate\Http\Request;

class UserBookingController extends Controller
{
    public function getDetails()
    {
        return view('pages.booking.get-details');
    }

    public function getBookingDetails(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' =>'required|email',
        ]);

        $booking = Booking::where('name', $request->name)->where('email', $request->email)->get();

        return view('pages.booking.show-booking-details')->with([
            'bookings' => $booking
        ]);
    }
}
