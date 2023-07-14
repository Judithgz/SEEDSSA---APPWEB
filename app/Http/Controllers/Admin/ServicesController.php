<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Services;
use App\Http\Controllers\Controller;

class ServicesController extends Controller
{

    
    public function index(){
        $services = Services::all();
        return view('services.index', compact('services'));
    }

    public function create(){
        return view('services.create');
    }


    public function sendData(Request $request){

        $rules = [
            'name' => 'required|min:5'
        ];


        $messages = [
            'name.required' => 'El nombre del servicio es obligatorio.',
            'name.min' => 'El nombre de la especialidad debe tener mas de 5 caracteres.'
        ];

        $this->validate($request, $rules, $messages);

        $services = new Services();
        $services->name = $request->input('name');
        $services->description = $request->input('description');
        $services->save();
        $notification = 'El servicio se ha creado correctamente.';

        return redirect('/servicios')->with(compact('notification'));
    }


    public function edit(Services $services){
        return view('/services.edit', compact('services'));
    }

    
    public function update(Request $request, Services $services){

        $rules = [
            'name' => 'required|min:5'
        ];


        $messages = [
            'name.required' => 'El nombre del servicio es obligatorio.',
            'name.min' => 'El nombre de la especialidad debe tener mas de 5 caracteres.'
        ];

        $this->validate($request, $rules, $messages);

        $services->name = $request->input('name');
        $services->description = $request->input('description');
        $services->save();

        $notification = 'El servicio se ha modificado correctamente.';

        return redirect('/servicios')->with(compact('notification'));
    }

    public function destroy(Services $services){

        $deleteName = $services->name;
        $services->delete();

        $notification = 'El servicio '. $deleteName .' se ha eliminado correctamente.';

        return redirect('/servicios')->with(compact('notification'));
    }

}