<?php

namespace App;

use App\Notifications\BookingStatusChangeNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;
use Illuminate\Support\Facades\Notification;

class Booking extends Model
{
    use SoftDeletes;

    public $table = 'bookings';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    const STATUS_SELECT = [
        'processing' => 'Processing',
        'confirmed'  => 'Confirmed',
        'rejected'   => 'Rejected',
    ];

    protected $fillable = [
        'ride_id',
        'name',
        'email',
        'phone',
        'seat_no',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
        'ticket_no',
        'bus_id'
    ];

    protected $casts = [
        'seat_no' => 'array',
    ];

    // public static function booting()
    // {
    //     self::updated(function (Booking $booking) {
    //         if ($booking->isDirty('status') && in_array($booking->status, ['confirmed', 'rejected'])) {
    //             Notification::route('mail', $booking->email)->notify(new BookingStatusChangeNotification($booking->status));
    //         }
    //     });
    // }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function ride()
    {
        return $this->belongsTo(Ride::class, 'ride_id');
    }

    public function getStatus()
    {
        $status = $this->status;

        switch($status){
            case 'processing':
                $data = '<span class="btn btn-sm btn-primary">Processing</span>';
                return $data;
                break;
            case 'rejected':
                $data = '<span class="btn btn-sm btn-danger">Rejected</span>';
                return $data;
                break;
            case 'confirmed':
                $data = '<span class="btn btn-sm btn-success">Confirm</span>';
                return $data;
                break;
        }
    }
}
