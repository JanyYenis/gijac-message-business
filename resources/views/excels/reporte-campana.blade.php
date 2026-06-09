<table class="table table-bordered">
    <tr>
        <th>
            <b>Nombre Campaña:</b>
        </th>
        <th>
            {{ $campana?->nombre ?? 'N/A' }}
        </th>
    </tr>
    <tr>
        <th>
            <b>Descripción:</b>
        </th>
        <th>
            {{ $campana?->descripcion ?? 'N/A' }}
        </th>
    </tr>
    <tr>
        <th>
            <b>Fecha de envio:</b>
        </th>
        <th>
            {{ $campana?->fecha_envio?->translatedFormat('j \d\e F \d\e\l Y') ?? 'N/A' }}
        </th>
    </tr>
    @if ($campana?->contenido_multimedia)
        <tr>
            <th><b>Imagen:</b></th>
            <th>Se insertó en el Excel (celda A5)</th>
        </tr>
    @endif
</table>
{{-- <table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Representate</th>
            <th>Nombre Cliente</th>
            <th>Etiqueta</th>
            <th>Panel</th>
            <th>Abierto</th>
            <th>Click (Respuesta)</th>
            <th>Click (Links)</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($datos as $dato)
            <tr>
                <td>{{$dato['cantidad'] ?? ''}}</td>
                <td>{{$dato['nombre_representante'] ?? ''}}</td>
                <td>{{$dato['nombre_medico'] ?? ''}}</td>
                <td>{{$dato['etiqueta'] ?? ''}}</td>
                <td>{{$dato['panel'] ?? ''}}</td>
                <td>{{$dato['participacion'] ?? ''}}</td>
                <td>{{$dato['click'] ?? ''}}</td>
                <td>{{$dato['click_links'] ?? ''}}</td>
            </tr>
        @endforeach
    </tbody>
</table> --}}
