@extends('mail.index')

@section('content')
    <div style="text-align:left; margin: 0 20px; padding: 40px; background-color:#ffffff; border-radius: 6px">
        <!--begin:Email content-->
        <div style="padding-bottom: 30px; font-size: 17px;">
            <strong>Hola!</strong>
        </div>

        <div style="padding-bottom: 30px">
            Está recibiendo este correo electrónico porque recibimos una solicitud de restablecimiento de contraseña para su
            cuenta. Para continuar con el restablecimiento de contraseña, haga clic en el botón a continuación:
        </div>

        <div style="padding-bottom: 40px; text-align:center;">
            <a href="{{$url}}" rel="noopener" target="_blank"
                style="text-decoration:none;display:inline-block;text-align:center;padding:0.75575rem 1.3rem;font-size:0.925rem;line-height:1.5;border-radius:0.35rem;color:#ffffff;background-color:#009ef7;border:0px;margin-right:0.75rem!important;font-weight:600!important;outline:none!important;vertical-align:middle">
                Restablecer la contraseña
            </a>
        </div>

        <div style="padding-bottom: 30px">
            Este enlace para restablecer la contraseña caducará en 60 minutos.
            Si no solicitó un restablecimiento de contraseña, no es necesario realizar ninguna otra acción.
        </div>

        <div style="border-bottom: 1px solid #eeeeee; margin: 15px 0"></div>

        <div style="padding-bottom: 50px; word-wrap: break-all;">
            <p style="margin-bottom: 10px;">
                ¿El botón no funciona? Intente pegar esta URL en su navegador:
            </p>

            <a href="{{$url}}" rel="noopener" target="_blank" style="text-decoration:none;color: #009ef7">
                {{$url}}
            </a>
        </div>
        <!--end:Email content-->
    </div>
@endsection
