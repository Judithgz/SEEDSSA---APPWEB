<?php
    use Illuminate\Support\Str;
?>

@extends('layouts.panel')

@section('styles')

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

@endsection

@section('content')

      <div class="card shadow">
        <div class="card-header border-0">
          <div class="row align-items-center">
            <div class="col">
              <h3 class="mb-0">Editar colaborador</h3>
            </div>
            <div class="col text-right">
              <a href="{{ url('/nosotros')}}" class="btn btn-sm btn-success">
              <i class="fas fa-chevron-left"></i>  
              Regresar</a>
            </div>
          </div>
        </div>

        <div class="card-body">

        @if($errors->any())
            @foreach($errors->all() as $error)
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-triangle"></i>
                <strong>Por favor!</strong>  {{ $error }}
                </div> 
            @endforeach
        @endif

            <form action="{{ url('/nosotros/'.$colaboradores->id)}}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Nombre del colaborador</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $colaboradores->name)}}">
                </div>

                <div class="form-group">
                  <label for="services"> Servicios </label>
                  <select name="services[]" id="services" class="form-control selectpicker"
                  data-style="btn-primary" title="Seleccionar servicios" multiple required>
                  @foreach($services as $servicio)
                    <option value="{{ $servicio->id }}">{{ $servicio->name}}</option>
                  @endforeach
                  </select>
                </div>

                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <input type="text" name="email" class="form-control" value="{{ old('email', $colaboradores->email)}}">
                </div>

                <div class="form-group">
                    <label for="phone">Teléfono</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $colaboradores->phone)}}">
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="text" name="password" class="form-control">
                    <small class="text-warning"> Solo llena el campo si desea cambiar la cotraseña</small>
                </div>

                <button type="submit" class="btn btn-sm btn-primary">Guardar cambios</button>
            </form>
        </div>

      </div>

@endsection

@section('scripts')

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

<script>
  $(document).ready(()=> {});
  $('#services').selectpicker('val', @json($services_ids));
</script>

@endsection