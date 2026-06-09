@foreach ($respuesta as $index => $items)
    <!--begin::Example-->
    <div class="separator separator-dashed separator-content border-primary my-15">
        <span class="h1 text-primary">{{$index}}</span>
    </div>
    <!--end::Example-->
    <div class="text-center">
        <div class="row">
            @foreach ($items as $item)
                <div class="col-lg-6 col-md-6 mb-7">
                    <label class="text-dark">{{$item['nombre_campo']}}</label>
                    @if (is_array($item['valor_campo']))
                        @foreach ($item['valor_campo'] as $dato)
                            <input type="text" readonly disabled class="form-control" value="{{$dato}}">    
                        @endforeach
                    @else
                        <input type="text" readonly disabled class="form-control" value="{{$item['valor_campo']}}">
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endforeach
