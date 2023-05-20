@extends('layouts.app')
@section('content')
<div class="card">
  <h5 class="card-header">Booking Records</h5>
  <div class="card-body">
    <div class="form-group">
        <a class="btn btn-default" href="{{ route('add.details') }}">
           Back
        </a>
    </div>
    @forelse($bookings as $book)
    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('add.details') }}">
                    Prints
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                        Ticket No: 
                        </th>
                        <td>
                        {{$book->ticket_no}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                        Email: 
                        </th>
                        <td>
                        {{$book->email}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                        Mobile No: 
                        </th>
                        <td>
                        {{$book->phone}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                        Booking Status: 
                        </th>
                        <td>
                        {!! $book->getStatus() !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                        Ride: 
                        </th>
                        <td>
                        From-{{$book->ride->departure_place}}, To-{{$book->ride->arrival_place}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                        Arrive Time: 
                        </th>
                        <td>
                        {{$book->ride->arrival_time}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                        Bus Name: 
                        </th>
                        <td>
                        {{$book->ride->bus->name}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                        Seat No: 
                        </th>
                        <td>
                        {{$book->seat_no ? implode(',',$book->seat_no) : 'Not Assign'}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                        Booked At: 
                        </th>
                        <td>
                        {{$book->created_at}}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

      @empty
        <p>Booking Not Available</p>
      @endforelse
  </div>
</div>
@endsection