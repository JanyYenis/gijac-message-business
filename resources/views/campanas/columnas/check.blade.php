@php
    $checked = 'checked';
    if (count($enviados)) {
        if (!in_array($model['id'], $enviados)) {
            $checked = '';
        }
    }
@endphp
<div class="form-check text-center">
    <input class="form-check-input checkSeleccionado" data-registro="{{$model['id']}}" type="checkbox" {{$checked}} value="1"/>
</div>
