<?php namespace App\Interfaces;

use Carbon\Carbon;

interface HorarioServiceInterface {

    public function isAvailableInterval($date, $colaboradoresId, Carbon $start);
    public function getAvailableIntervals($date, $colaboradoresId);

}