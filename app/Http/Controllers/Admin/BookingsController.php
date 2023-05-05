<?php

namespace App\Http\Controllers\Admin;

use App\Booking;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyBookingRequest;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Ride;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class BookingsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('booking_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bookings = Booking::orderBy('created_at','desc')->get();

        $rides = Ride::get();

        $selectedRide = null;

        if (request()->input('ride_id')) {
            $selectedRide = optional($rides->find(request()->input('ride_id')))->route;
        }

        return view('admin.bookings.index', compact('bookings', 'rides', 'selectedRide'));
    }

    public function create()
    {
        abort_if(Gate::denies('booking_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rides = Ride::all()->pluck('route', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.bookings.create', compact('rides'));
    }

    public function store(StoreBookingRequest $request)
    {
        // $booking = Booking::create($request->all());
        $ride = Ride::find($request->ride_id);
        $bus_id = $ride->bus->id;


        DB::transaction(function()use($ride,$bus_id,$request){
           $booking = Booking::create([
                'ticket_no' => $this->ticket(),
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'ride_id' => $ride->id,
                'bus_id' => $bus_id,
                'status' => $request->status
            ]);
        });

        return redirect()->route('admin.bookings.index');
    }

    public function edit(Booking $booking)
    {
        abort_if(Gate::denies('booking_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rides = Ride::all()->pluck('route', 'id')->prepend(trans('global.pleaseSelect'), '');

        $maximum_seats = $booking->ride->bus->maximum_seats;
    
        $allotedSeats = collect($booking->seat_no);

        $remaining_seats =$booking->ride->bus->remaining_seat;

        $getAllotedSeats = $booking->ride->bus->getAllotedSeat();

        // dd($getSeats);


        $booking->load('ride');

        return view('admin.bookings.edit', compact('rides','getAllotedSeats','remaining_seats', 'booking','maximum_seats','allotedSeats'));
    }

    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        // dd($request);
        DB::transaction(function()use($booking, $request){
            $booking->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'status' => $request->status,
                'seat_no' => $request->seat_no ? $request->seat_no : $booking->seat_no
            ]);

            // dd($booking);
        });
        // $booking->update($request->all());

        return redirect()->route('admin.bookings.index');
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

    public function show(Booking $booking)
    {
        abort_if(Gate::denies('booking_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $booking->load('ride');

        return view('admin.bookings.show', compact('booking'));
    }

    public function destroy(Booking $booking)
    {
        abort_if(Gate::denies('booking_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $booking->delete();

        return back();
    }

    public function massDestroy(MassDestroyBookingRequest $request)
    {
        Booking::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
