<div class="table-responsive">
          <!-- Projects table -->
          <table class="table align-items-center table-flush">
            <thead class="thead-light">
              <tr>
                <th scope="col">Descripción</th>
                <th scope="col">Servicio</th>
                @if($role == 'paciente')
                <th scope="col">Especialista</th>
                @elseif($role == 'colaboradores')
                <th scope="col">Paciente</th>
                @endif          

                <th scope="col">Fecha</th>
                <th scope="col">Hora</th>
                <th scope="col">Tipo</th>
                <th scope="col">Opciones</th>
              </tr>
            </thead>
            <tbody>
                @foreach($pendingAppointments as $cita)
              <tr>
                <th scope="row">
                  {{ $cita->description}}
                </th>
                <td>
                  {{ $cita->services->name}}
                </td>
                @if($role == 'paciente')
                  <td>
                    {{ $cita->colaboradores->name}}
                  </td>
                @elseif($role == 'colaboradores')
                  <td>
                    {{ $cita->patient->name}}
                  </td>
                @endif
                <td>
                  {{ $cita->scheduled_date}}
                </td>
                <td>
                  {{ $cita->Scheduled_Time_12}}
                </td>
                <td>
                  {{ $cita->type}}
                </td>
                <td>


                @if($role == 'admin')
                    <a href="{{ url('/miscitas/'.$cita->id) }}" class="btn btn-sm btn-info" title="Ver cita">
                    <i class="ni far fa-eye"></i>
                    </a>
                  @endif

                @if($role == 'colaboradores' || $role == 'admin')
                  <form action="{{ url('/miscitas/'.$cita->id.'/confirm')}}" method="POST"
                    class="d-inline-block" >
                    @csrf
                
                    <button type="submit" class="btn btn-sm btn-success" title="Confirmar cita">
                      <i class="ni ni-check-bold"></i>
                    </button>
                  </form>
                  @endif

                  <form action="{{ url('/miscitas/'.$cita->id.'/cancel')}}" method="POST"
                    class="d-inline-block">
                    @csrf
                
                    <button type="submit" class="btn btn-sm btn-danger" title="Cancelar cita">
                    <i class="ni ni-fat-delete"></i>
                    </button>
                  </form>
                  
                </td> 

              </tr>
                @endforeach
            </tbody>
          </table>
        </div>