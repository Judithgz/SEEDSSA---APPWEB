<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Interfaces\HorarioServiceInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;


class StoreAppointment extends FormRequest
{

    private $horarioService;

    public function __construct(HorarioServiceInterface $horarioServiceInterface)
    {
        $this->horarioService = $horarioServiceInterface;
    }


    public function rules()
    {
        return [
            'scheduled_time' => 'required',
            'type' => 'required',
            'description' => 'required',
            'colaboradores_id' => 'exists:users,id',
            'services_id' => 'exists:services,id'
        ];
    }

    public function messages()
    {
        return [
                'scheduled_time.required' => 'Debe seleccionar una hora válida para su cita.',
                'type.required' => 'Debe seleccionar el tipo de consula.',
                'description.required' => 'Nos importa leer tus comentarios!'
        ];
    }


    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            
        $date = $this->input('scheduled_date');
        $colaboradoresId = $this->input('colaboradores_id');
        $scheduled_time = $this->input('scheduled_time');

            if($date && $colaboradoresId && $scheduled_time){
                $start = new Carbon($scheduled_time);
            }else {
                return;
            }

            if (!$this->horarioService->isAvailableInterval($date, $colaboradoresId, $start)) {
                $validator->errors()->add(
                    'available_time', 'La hora seleccionada ya se encuentra reservada, ups!'
                );
            }
    });
}
}
