@props(['url', 'texto' => 'Ver detalles'])

<table role="presentation" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center" style="border-radius: 6px; background-color:#2E8B47;">
            <a href="{{ $url }}"
               target="_blank"
               style="display:inline-block; padding: 12px 28px; font-family: Arial, sans-serif; font-size:14px; font-weight:bold; color:#FFFFFF; text-decoration:none; border-radius:6px;">
                {{ $texto }}
            </a>
        </td>
    </tr>
</table>
