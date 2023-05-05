<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

class Bus extends Model
{
    use SoftDeletes;

    public $table = 'buses';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'places_available',
        'maximum_seats',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getSelectNameAttribute()
    {
        return $this->name . ' (' . $this->places_available . ' ' . \Str::plural('place', $this->places_available) . ')';
    }

    public function getRemainingSeatAttribute()
    {
        $buses = Booking::where('bus_id', $this->id)->get();
        $count = 0;
        foreach($buses as $bus){
            $seat = collect($bus->seat_no)->count();

            $count = $count + $seat;
        }

        // return $count;

        $remaining_seat = intval($this->maximum_seats) - intval($count);

        return $remaining_seat;
    }

    public function getAllotedSeat()
    {
        $buses = Booking::where('bus_id', $this->id)->get();
        $seats = collect();
        foreach($buses as $bus){
            $seat = collect($bus->seat_no);

            $seats = $seats->merge($seat);
        }

        $merge = $seats;

        // return $count;
        return $merge;
    }
}
