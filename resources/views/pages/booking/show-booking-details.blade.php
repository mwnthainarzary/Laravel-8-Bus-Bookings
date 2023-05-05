@extends('layouts.app')
@section('content')
<div class="card">
  <h5 class="card-header">Booking Records</h5>
  <div class="card-body">
    @foreach($bookings as $book)
        <p>Name: {{$book->name}}</p>
        <p>Email: {{$book->email}}</p>
        <p>Mobile No: {{$book->phone}}</p>
        <p>Booking Status: {{$book->status}}</p>
        <p>Ride: From-{{$book->ride->departure_place}}, To-{{$book->ride->arrival_place}}</p>
        <p>Arrive Time: {{$book->ride->arrival_time}}</p>
        <p>Bus Name: {{$book->ride->bus->name}}</p>
    @endforeach
  </div>
</div>

<div class="row">
  <div class="col-sm-6">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Special title treatment</h5>
        <div>
        @foreach($bookings as $book)
            <p>{{$book->name}}</p>
        @endforeach
        </div>
        <a href="#" class="btn btn-primary">Go somewhere</a>
      </div>
    </div>
  </div>
  <div class="col-sm-6">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Special title treatment</h5>
        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
        <a href="#" class="btn btn-primary">Go somewhere</a>
      </div>
    </div>
  </div>
</div>

@endsection