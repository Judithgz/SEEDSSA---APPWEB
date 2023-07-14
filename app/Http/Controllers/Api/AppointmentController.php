<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreAppointment;
use App\Models\Appointment;



class AppointmentController extends Controller
{
    public function index(){
        $user = Auth::guard('api')->user();
        $appointments = $user->asPatientAppointments()
            ->with(['services' => function($query) {
                $query->select('id', 'name');
            }
            , 'colaboradores' => function($query) {
                $query->select('id', 'name');
            }
            ])
            ->get([
                "id",
                "scheduled_date",
                "scheduled_time",
                "type",
                "description",
                "colaboradores_id",
                "services_id",
                "status"
            ]);
        
        return $appointments;
    }

    public function store(StoreAppointment $request){
        $patientId = Auth::guard('api')->id();
        $appointment = Appointment::createForPatient($request, $patientId);

        if($appointment)
            $success = true;
            else
            $success = false;

            return compact('success');
    }
}
