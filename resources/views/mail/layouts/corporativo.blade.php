<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'GIJAC WhatsApp')</title>
</head>
<body style="margin:0; padding:0; background-color:#F4F6F5; font-family: Arial, Helvetica, sans-serif;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#F4F6F5; padding: 30px 0;">
        <tr>
            <td align="center">

                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background-color:#FFFFFF; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,0.08);">

                    {{-- HEADER --}}
                    <tr>
                        <td style="background-color:#1F5C30; padding: 24px 32px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="left" valign="middle">
                                        <img src="{{ $logoUrl ?? asset('img/logo_gmb.png') }}"
                                             alt="GMB"
                                             width="48"
                                             style="display:block; border-radius: 50%;">
                                    </td>
                                    <td align="left" valign="middle" style="padding-left: 12px;">
                                        <span style="color:#FFFFFF; font-size: 18px; font-weight:bold; font-family: Arial, sans-serif;">
                                            GIJAC MESSAGE BUSINESS
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- BANDA VERDE SECUNDARIA (acento) --}}
                    <tr>
                        <td style="background-color:#2E8B47; height: 4px; line-height:4px; font-size:0;">&nbsp;</td>
                    </tr>

                    {{-- TÍTULO DEL CORREO --}}
                    <tr>
                        <td style="padding: 32px 32px 8px 32px;">
                            <h1 style="margin:0; font-size: 22px; color:#1F5C30; font-family: Arial, sans-serif;">
                                @yield('titulo')
                            </h1>
                        </td>
                    </tr>

                    {{-- CONTENIDO --}}
                    <tr>
                        <td style="padding: 8px 32px 32px 32px; color:#333333; font-size: 14px; line-height: 1.6; font-family: Arial, sans-serif;">
                            @yield('contenido')
                        </td>
                    </tr>

                    {{-- BOTÓN CTA (opcional por vista) --}}
                    @hasSection('boton')
                        <tr>
                            <td align="center" style="padding: 0 32px 32px 32px;">
                                @yield('boton')
                            </td>
                        </tr>
                    @endif

                    {{-- SEPARADOR --}}
                    <tr>
                        <td style="padding: 0 32px;">
                            <div style="border-top: 1px solid #E5E5E5;"></div>
                        </td>
                    </tr>

                    {{-- FOOTER --}}
                    <tr>
                        <td style="padding: 24px 32px; font-family: Arial, sans-serif;">
                            <p style="margin:0 0 6px 0; font-size:12px; color:#888888;">
                                Este correo fue generado automáticamente por la plataforma GMB WhatsApp Business.
                            </p>
                            <p style="margin:0; font-size:12px; color:#888888;">
                                © {{ date('Y') }} GIJAC. Todos los derechos reservados.
                            </p>
                        </td>
                    </tr>

                </table>

                {{-- TEXTO FUERA DE LA TARJETA --}}
                <table role="presentation" width="600" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="center" style="padding-top: 16px; font-family: Arial, sans-serif;">
                            <p style="margin:0; font-size:11px; color:#AAAAAA;">
                                Si no esperabas este correo, puedes ignorarlo con confianza.
                            </p>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>

</body>
</html>
