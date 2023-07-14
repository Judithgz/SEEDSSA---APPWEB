<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Services;


class UsController extends Controller
{

    public function index()
    {
        $us = User::us()->paginate(15);
        return view('us.index', compact('us'));
    }


    public function create()
    {
        $services = Services::all();
        return view('us.create', compact('services'));
    }

 
    public function store(Request $request)
    {

        $rules = [
            'name' => 'required|min:3',
            'email' => 'required|email',
            'phone' => 'required',
        ];
        $messages = [
            'name.required' => 'El nombre del colaborador es obligatorio.',
            'name.min' => 'El nombre debe tener más de 3 carácteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingresa una dirección de correo electrónico valida.',
            'phone.required' => 'El numero de teléfono es obligatorio',
        ];

        $this->validate($request, $rules, $messages);

        $user = User::create(
        $request->only('name', 'email', 'phone')
        + [
            'role' => 'colaboradores',
            'password' => bcrypt($request->input('password'))
        ]
    );

    $user->services()->attach($request->input('services'));

    $notification = 'El colaborador se ha registrado correctamente.';
    return redirect('/nosotros')->with(compact('notification'));
}



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $colaboradores = User::us()->findOrFail($id);

        $services = Services::all();
        $services_ids = $colaboradores->services()->pluck('services.id');

        return view('us.edit', compact('colaboradores', 'services', 'services_ids'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'name' => 'required|min:3',
            'email' => 'required|email',
            'phone' => 'required',
        ];
        $messages = [
            'name.required' => 'El nombre del colaborador es obligatorio.',
            'name.min' => 'El nombre debe tener más de 3 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingresa una dirección de correo electrónico valida.',
            'phone.required' => 'El numero de teléfono es obligatorio',
        ];

        $this->validate($request, $rules, $messages);
        $user = User::us()->findOrFail($id);

        $data = $request->only('name', 'email', 'phone');
        $password = $request->input('password');

        if($password)
        $data['password'] = bcrypt($password);

        $user->fill($data);
        $user->save();
        $user->services()->sync($request->input('services'));

        $notification = 'La información se ha modificado correctamente.';
        return redirect('/nosotros')->with(compact('notification'));
    }


    public function destroy(string $id)
    {
        $user = User::us()->findOrFail($id);
        $colabName = $user->name;
        $user->delete();

        $notification = "El colaborador '. $colabName .' se elimino correctamente.";

        return redirect('/nosotros')->with(compact('notification'));
    }
}

