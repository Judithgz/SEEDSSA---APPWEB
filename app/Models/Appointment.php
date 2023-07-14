<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;


class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'scheduled_date',
        'scheduled_time',
        'type',
        'description',
        'colaboradores_id',
        'patient_id',
        'services_id'
    ];

    protected $hidden = [
        'scheduled_time',
        'services_id',
        'colaboradores_id'
    ];

    protected $appends = [
        'scheduled_time_12'
    ];

    public function services(){
        return $this->belongsTo(Services::class);
    }

    public function colaboradores(){
        return $this->belongsTo(User::class);
    }

    public function patient(){
        return $this->belongsTo(User::class);
    }

    public function getScheduledTime12Attribute(){
        return (new Carbon($this->scheduled_time))
        ->format('g:i A');
    }

    public function cancellation(){
        return $this->hasOne(CancelledAppointment::class);
    }

    static public function createForPatient(Request $request, $patientId){
        $data = $request->only([
            'scheduled_date',
            'scheduled_time',
            'type',
            'description',
            'colaboradores_id',
            'services_id'
        ]);
        $data['patient_id'] = $patientId;

        $carbonTime = Carbon::createFromFormat('g:i A', $data['scheduled_time']);
        $data['scheduled_time'] = $carbonTime->format('H:i:s');

        return self::create($data);
    }
}
