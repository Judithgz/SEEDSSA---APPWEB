<?php

namespace App\Http\Controllers\Api;

use App\Models\Services;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServicesController extends Controller
{

    public function index(){
        return Services::all(['id', 'name']);
    }

    public function especialistas(Services $services){
        return $services->users()->get([
            'users.id',
            'users.name'
        ]);
    }
}
