<?php namespace App\Servicios;

use App\Models\Appointment;
use App\Models\Horarios;
use App\Interfaces\HorarioServiceInterface;
use Carbon\Carbon; 

class HorarioService implements HorarioServiceInterface {

    private function getDayFromDate($date){
        $dateCarbon = new Carbon($date);
        $i = $dateCarbon->dayOfWeek;
        $day = ($i==0 ? 6 : $i-1);
        return $day;
    }

    public function isAvailableInterval($date, $colaboradoresId, Carbon $start){
        $exists = Appointment::where('colaboradores_id', $colaboradoresId)
            ->where('scheduled_date', $date)
            ->where('scheduled_time', $start->format('H:i:s'))
            ->exists();

            return !$exists;
    }

    public function getAvailableIntervals($date, $colaboradoresId){
        $horario = Horarios::where('active', true)
        ->where('day', $this->getDayFromDate($date))
        ->where('user_id', $colaboradoresId)
        ->first([
            'morning_start', 'morning_end', 
            'afternoon_start', 'afternoon_end'
        ]);

        if($horario){
            
            $morningIntervalos = $this->getIntervalos(
                $horario->morning_start, $horario->morning_end, $colaboradoresId, $date
            );

            $afternoonIntervalos = $this->getIntervalos(
                $horario->afternoon_start, $horario->afternoon_end, $colaboradoresId, $date
            );
        }else {
            $morningIntervalos = [];
            $afternoonIntervalos = [];
        }


        $data = [];
        $data['morning'] = $morningIntervalos;
        $data['afternoon'] = $afternoonIntervalos;
        return $data;
    }

    private function getIntervalos($start, $end, $colaboradoresId, $date){
        $start = new Carbon($start);
        $end = new Carbon($end);

        $intervalos = [];
        while($start < $end){
            $intervalo = [];
            $intervalo['start'] = $start->format('g:i A');

            $available = $this->isAvailableInterval($date, $colaboradoresId, $start);

            $start->addMinutes(30);
            $intervalo['end'] = $start->format('g:i A');

            if($available){
                $intervalos [] = $intervalo;
            }

        }
        return $intervalos;

    }

}