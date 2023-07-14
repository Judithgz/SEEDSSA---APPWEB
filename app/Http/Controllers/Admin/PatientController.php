<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;


class PatientController extends Controller
{
    public function index()
    {
        $patients = User::patients()->paginate(15);
        return view('patients.index', compact('patients'));
    }


    public function create()
    {
        return view('patients.create');
    }

 
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|min:3',
            'email' => 'required|email',
            'phone' => 'required',
        ];
        $messages = [
            'name.required' => 'El nombre del paciente es obligatorio.',
            'name.min' => 'El nombre debe tener más de 3 carácteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingresa una dirección de correo electrónico valida.',
            'phone.required' => 'El numero de teléfono es obligatorio',
        ];

        $this->validate($request, $rules, $messages);

        User::create(
        $request->only('name', 'email', 'phone')
        + [
            'role' => 'paciente',
            'password' => bcrypt($request->input('password'))
        ]
    );
    $notification = 'El paciente se ha registrado correctamente.';
    return redirect('/pacientes')->with(compact('notification'));
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
    public function edit(string $id)
    {
        $patient = User::Patients()->findOrFail($id);
        return view('patients.edit', compact('patient'));
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
            'name.required' => 'El nombre del paciente es obligatorio.',
            'name.min' => 'El nombre debe tener más de 3 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingresa una dirección de correo electrónico valida.',
            'phone.required' => 'El numero de teléfono es obligatorio',
        ];

        $this->validate($request, $rules, $messages);
        $user = User::Patients()->findOrFail($id);

        $data = $request->only('name', 'email', 'phone');
        $password = $request->input('password');

        if($password)
        $data['password'] = bcrypt($password);

        $user->fill($data);
        $user->save();

        $notification = 'La información se ha modificado correctamente.';
        return redirect('/pacientes')->with(compact('notification'));
    }


    public function destroy(string $id)
    {
        $user = User::Patients()->findOrFail($id);
        $PacienteName = $user->name;
        $user->delete();

        $notification = "El paciente '. $PacienteName .' se elimino correctamente.";

        return redirect('/pacientes')->with(compact('notification'));
    }
}
