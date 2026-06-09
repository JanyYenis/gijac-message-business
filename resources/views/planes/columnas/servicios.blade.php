@foreach($model->servicios as $servicio)
    <span class="badge-service {{ $servicio->pivot->habilitado ? 'active' : 'inactive' }}">
        <i class="fas {{ $servicio->pivot->habilitado ? 'fa-check' : 'fa-times' }}"></i>
        {{ $servicio->nombre }}
    </span>
@endforeach
