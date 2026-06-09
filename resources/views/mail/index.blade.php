<!DOCTYPE html>
<html>

<head>
    <style>
        html,
        body {
            padding: 0;
            margin: 0;
        }
    </style>
</head>

@section('css')
@show

<body>
    {{-- <div style="font-family:Arial,Helvetica,sans-serif; line-height: 1.5; font-weight: normal; font-size: 15px; color: #2F3044; min-height: 100%; margin:0; padding:0; width:100%; background-color:#edf2f7">
        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;margin:0 auto; padding:0; max-width:600px">
            <tbody>
                <tr>
                    <td align="center" valign="center" style="text-align:center; padding: 40px">
                        <a href="{{ route('home') }}" rel="noopener" target="_blank">
                            <img alt="Logo" src="https://message-business.gijac.com/img/GMB.png" width="40%">
                        </a>
                    </td>
                </tr>
                <tr>
                    @yield('content')
                </tr>

                <tr>
                    <td align="center" valign="center"
                        style="font-size: 13px; text-align:center;padding: 20px; color: #6d6e7c;">
                        <p>Pensando en ti.</p>
                        <p> Copyright © <a href="https://gijac.com" rel="noopener" target="_blank">GIJAC WEB</a>.
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div> --}}

    <div>
        <!--begin::Email template-->
		<style>
            html,body {
                padding:0;
                margin:0;
                font-family: Inter, Helvetica, "sans-serif";
            }

			a:hover {
                color: #009ef7;
            }
        </style>
        <div id="#kt_app_body_content"
            style="background-color:#D5D9E2; font-family:Arial,Helvetica,sans-serif; line-height: 1.5; min-height: 100%; font-weight: normal; font-size: 15px; color: #2F3044; margin:0; padding:0; width:100%; margin-top: 2rem;">
            <div
                style="background-color:#ffffff; padding: 45px 0 34px 0; border-radius: 24px; margin:40px auto; max-width: 600px;">
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" height="auto"
                    style="border-collapse:collapse">
                    <tbody>
                        <tr>
                            <td align="center" valign="center" style="text-align:center; padding-bottom: 10px">

                                <!--begin:Email content-->
                                <div style="text-align:center; margin:0 15px 34px 15px">
                                    <!--begin:Logo-->
                                    <div style="margin-bottom: 10px">
                                        <a href="{{ route('home') }}" rel="noopener" target="_blank">
                                            <img alt="Logo" src="https://message-business.gijac.com/img/logo_gmb.png"
                                                style="height: 35px">
                                        </a>
                                    </div>
                                    <!--end:Logo-->
                                </div>
                            </td>
                        </tr>

                        <tr style="display: flex; justify-content: center; margin:0 60px 35px 60px">
                            <td align="start" valign="start" style="padding-bottom: 10px;">
                                @yield('content')
                            </td>
                        </tr>


                        <tr>
                            <td align="center" valign="center"
                                style="font-size: 13px; text-align:center; padding: 0 10px 10px 10px; font-weight: 500; color: #A1A5B7; font-family:Arial,Helvetica,sans-serif">
                                <p
                                    style="color:#181C32; font-size: 16px; font-weight: 600; margin-bottom:9px                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               ">
                                    Soporte</p>
                                <p style="margin-bottom:2px">Llama a nuestro número de atención al cliente: +57 (317) 178 - 9584</p>
                                <p style="margin-bottom:4px">Puedes contactar con nosotros en <a href="{{ route('contactarnos') }}"
                                        rel="noopener" target="_blank" style="font-weight: 600">message-business.gijac.com</a>.</p>
                                <p>Atendemos de lunes a viernes de 9:00 AM a 5:30 PM.</p>
                            </td>
                        </tr>

                        <tr>
                            <td align="center" valign="center" style="text-align:center; padding-bottom: 20px;">
                                <a href="#" style="margin-right:10px">
                                    <img alt="Logo"
                                        src="/metronic8/demo29/assets/media/email/icon-linkedin.svg"></a>
                                <a href="https://www.facebook.com/share/1AgqGKJ5Dj" style="margin-right:10px">
                                    <img alt="Logo"
                                        src="https://www.facebook.com/share/1AgqGKJ5Dj/"></a>
                            </td>
                        </tr>

                        <tr>
                            <td align="center" valign="center"
                                style="font-size: 13px; padding:0 15px; text-align:center; font-weight: 500; color: #A1A5B7;font-family:Arial,Helvetica,sans-serif">
                                <p> © Copyright GIJAC WEB.
                                    <a href="https://gijac.com" rel="noopener" target="_blank"
                                        style="font-weight: 600;font-family:Arial,Helvetica,sans-serif">Cancelar suscripción</a>&nbsp;.
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @section('scripts')
    @show
</body>

</html>
