<?php

namespace Database\Seeders;

use App\Models\Services;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            'Consejería y Orientación',
            'Inyección anticonceptiva',
            'Pastillas anticonceptivas',
            'Pastilla de emergencia con consejeria',
            'Prueba de embarazo',
            'Pruebas de VIH y Sífilis',
            'Prueba de clamidia',
            'Prueba de gonorrea',
            'Revisión p/detección de infecciones',
            'Papanicolau',
            'Colocación de DIU',
            'Retirar DIU',
            'Revisión de DIU',
            'Colocación de Implante Subdérmico',
            'Retiro de Implante Subdérmico',
            'Orientación psicológica'
        ];
        foreach($services as $serviceName) {
            $service = Services::create([
                'name'=> $serviceName
            ]);
            $service->users()->saveMany(
                User::factory(4)->state(['role' => 'colaboradores'])->make()
            );
        }
        User::find(3)->services()->save($service);
    }
}
