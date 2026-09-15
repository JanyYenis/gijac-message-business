@extends('mail.layouts.corporativo')

@section('titulo', 'Reporte de predicción de campaña')

@section('contenido')
    <p>{{ __('Hola,') }}</p>

    <p>{{ __('Tu campaña ya fue analizada. Estos son los datos principales:') }}</p>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin: 16px 0;">
        <tr>
            <td style="padding: 8px 0; border-bottom: 1px solid #EEEEEE;"><strong>{{ __('Total de contactos') }}</strong></td>
            <td style="padding: 8px 0; border-bottom: 1px solid #EEEEEE; text-align:right;">{{ $totalContactos }}</td>
        </tr>
        <tr>
            <td style="padding: 8px 0; border-bottom: 1px solid #EEEEEE;"><strong>{{ __('Tasa histórica promedio') }}</strong></td>
            <td style="padding: 8px 0; border-bottom: 1px solid #EEEEEE; text-align:right;">{{ $promedioApertura }}%</td>
        </tr>
        <tr>
            <td style="padding: 8px 0;"><strong>{{ __('Nivel de apertura general') }}</strong></td>
            <td style="padding: 8px 0; text-align:right;">
                <span style="background-color:
                    @if($nivelGeneral === 'Alta') #D9F2E3
                    @elseif($nivelGeneral === 'Media') #FFF4CC
                    @else #FDE2E1
                    @endif;
                    color:#333333; padding: 3px 10px; border-radius: 12px; font-size:12px; font-weight:bold;">
                    {{ $nivelGeneral }}
                </span>
            </td>
        </tr>
    </table>

    @if($explicacion)
        <p><strong>{{ __('Análisis IA:') }}</strong> {{ $explicacion }}</p>
    @endif

    <p>{{ __('Adjunto encontrarás el Excel con el detalle completo por contacto, incluyendo probabilidad de apertura, nivel de confianza y mejor hora de envío.') }}</p>
@endsection

{{-- Opcional: si tienes un link al panel para ver el reporte online --}}
{{--
@section('boton')
    <x-emails.components.boton :url="route('campanas.reporte', $campanaId)" texto="Ver reporte completo" />
@endsection
--}}
