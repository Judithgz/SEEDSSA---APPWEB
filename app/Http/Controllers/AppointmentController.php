<?php

namespace App\Http\Controllers;

use App\Interfaces\HorarioServiceInterface;
use Carbon\Carbon;
use App\Models\Services;
use App\Models\Appointment;
use App\Models\CancelledAppointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreAppointment;


class AppointmentController extends Controller
{

    public function index(){


        $role = auth()->user()->role;

        if($role == 'admin'){
            //Admin
            $confirmedAppointments = Appointment::all()
            ->where('status', 'Confirmada');
            $pendingAppointments = Appointment::all()
            ->where('status', 'Reservada');
            $oldAppointments = Appointment::all()
            ->whereIn('status', ['Atendida', 'Cancelada']);
        }elseif($role == 'colaboradores'){
            //Especialistas
            $confirmedAppointments = Appointment::all()
            ->where('status', 'Confirmada')
            ->where('colaboradores_id', auth()->id());
            $pendingAppointments = Appointment::all()
            ->where('status', 'Reservada')
            ->where('colaboradores_id', auth()->id());
            $oldAppointments = Appointment::all()
            ->whereIn('status', ['Atendida', 'Cancelada'])
            ->where('colaboradores_id', auth()->id());
        }elseif($role == 'paciente'){
            //Pacientes
            $confirmedAppointments = Appointment::all()
            ->where('status', 'Confirmada')
            ->where('patient_id', auth()->id());
            $pendingAppointments = Appointment::all()
            ->where('status', 'Reservada')
            ->where('patient_id', auth()->id());
            $oldAppointments = Appointment::all()
            ->whereIn('status', ['Atendida', 'Cancelada'])
            ->where('patient_id', auth()->id());
        }

        return view('appointments.index', 
        compact('confirmedAppointments', 'pendingAppointments', 'oldAppointments', 'role'));
    }

    public function create(HorarioServiceInterface $horarioServiceInterface) {
        $services = Services::all();

        $servicesId = old('services_id');
        if($servicesId){
            $service = Services::find($servicesId);
            $especialistas = $service->users;
        } else {
            $especialistas = collect();
        }

        $date = old('scheduled_date');
        $colaboradoresId = old('colaboradores_id');
        if($date && $colaboradoresId){
            $intervals = $horarioServiceInterface->getAvailableIntervals($date, $colaboradoresId);
        } else {
            $intervals = null;
        }

        return view('appointments.create', compact('services', 'especialistas', 'intervals'));
    }




    public function store(StoreAppointment $request, HorarioServiceInterface $horarioServiceInterface){

        $created = Appointment::createForPatient($request, auth()->id());


        if($created)
            $notification = 'La cita se ha realizado correctamente.';
        else
            $notification = 'Error al registrar la cita.';


        return redirect('/miscitas')->with(compact('notification'));
    }



    public function cancel(Appointment $appointment, Request $request){

        if($request->has('justification')){
            $cancellation = new CancelledAppointment();
            $cancellation->justification = $request->input('justification');
            $cancellation->cancelled_by_id = auth()->id();

            $appointment->cancellation()->save($cancellation);
        }

        $appointment->status = 'Cancelada';
        $appointment->save();
        $notification = 'La cita se ha cancelado correctamente.';

        return redirect('/miscitas')->with(compact('notification'));
    }

    public function confirm(Appointment $appointment){


        $appointment->status = 'Confirmada';
        $appointment->save();
        $notification = 'La cita se ha confirmado correctamente.';

        return redirect('/miscitas')->with(compact('notification'));
    }

    public function formCancel(Appointment $appointment){
        if($appointment->status == 'Confirmada'){
            $role = auth()->user()->role;
            return view('appointments.cancel', compact('appointment', 'role'));
        }

        return redirect('/miscitas');
    }

    public function show(Appointment $appointment){
        $role = auth()->user()->role;
        return view('appointments.show', compact('appointment', 'role'));
    }
}
