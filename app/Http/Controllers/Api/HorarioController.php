<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\Horarios;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Interfaces\HorarioServiceInterface;


class HorarioController extends Controller
{
    public function hours(Request $request, HorarioServiceInterface $horarioServiceInterface){

        $rules = [
            'date' => 'required|date_format:"Y-m-d"',
            'colaboradores_id' => 'required|exists:users,id'
        ];

        $this->validate($request, $rules);

        $date = $request->input('date');
        $colaboradoresId = $request->input('colaboradores_id');

        return $horarioServiceInterface->getAvailableIntervals($date, $colaboradoresId);
        
    }

    
}
