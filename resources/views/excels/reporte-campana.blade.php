<table class="table table-bordered">
    <tr>
        <th>
            <b>{{ __('Nombre Campaña:') }}</b>
        </th>
        <th>
            {{ $campana?->nombre ?? 'N/A' }}
        </th>
    </tr>
    <tr>
        <th>
            <b>{{ __('Descripción:') }}</b>
        </th>
        <th>
            {{ $campana?->descripcion ?? 'N/A' }}
        </th>
    </tr>
    <tr>
        <th>
            <b>{{ __('Fecha de envio:') }}</b>
        </th>
        <th>
            {{ $campana?->fecha_envio?->translatedFormat('j \d\e F \d\e\l Y') ?? 'N/A' }}
        </th>
    </tr>
    @if ($campana?->contenido_multimedia)
        <tr>
            <th><b>{{ __('Imagen:') }}</b></th>
            <th>{{ __('Se insertó en el Excel (celda A5)') }}</th>
        </tr>
    @endif
</table>
