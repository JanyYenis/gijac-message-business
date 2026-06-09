<div class="row">
    <div class="col-lg-8 col-8">
        <div class="">
            <div class="text-center mb-2">
                <h3 class="text-gijac">Información de contacto</h3>
                <span>Añade algunos detalles de contacto para tu empresa</span>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="fv-row mb-3">
                        <label class="form-label">Foto de perfil</label>
                        <input type="file" class="form-control inputFile" name="profile_picture_url" id="inputFile" accept="image/png, image/jpeg, image/jpg"/>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="fv-row mb-3">
                        <label class="form-label required">Info</label>
                        <input type="text" class="form-control" name="about" placeholder="about"
                            value="{{ $dato?->about ?? '' }}" required />
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="fv-row mb-3">
                        <label class="required form-label">Email</label>
                        <input type="text" class="form-control" name="email" id="emailPerfil" placeholder="email"
                            value="{{ $dato?->email ?? '' }}" required />
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="fv-row mb-3">
                        <label class="required form-label">Categoria</label>
                        <select name="vertical" id="selectCategoria" data-control="select2" required
                            class="form-control selectCategoria" data-placeholder="Categoria" data-allow-clear="true"
                            data-hide-search="true" data-dropdown-parent="body">
                            @foreach ($categorias as $index => $categoria)
                                <option value="{{ $index }}" {{ $index == $dato?->vertical ? 'selected' : '' }}>
                                    {{ $categoria }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="fv-row mb-3">
                        <label class="required form-label">Sitio Web</label>
                        <!--begin::Repeater-->
                        @if (isset($dato?->websites) && count($dato?->websites))
                            @foreach ($dato?->websites as $web)
                                <input type="text" class="form-control sitiosWebs" name="websites" placeholder="https://www.ejemplo.com"
                                    value="{{ $web }}" />
                            @endforeach
                        @endif
                        <div class="kt_docs_repeater_basic">
                            <!--begin::Form group-->
                            <div class="form-group">
                                <div data-repeater-list="kt_docs_repeater_basic">
                                    <div data-repeater-item>
                                        <div class="form-group row mt-2">
                                            <div class="col-md-10">
                                                <input type="text" class="form-control sitiosWebs" name="websites[]" placeholder="https://www.ejemplo.com"/>
                                            </div>
                                            <div class="col-md-2">
                                                <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger">
                                                    <i class="fas fa-trash text-danger fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                                    Eliminar
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Form group-->

                            <!--begin::Form group-->
                            <div class="form-group mt-5">
                                <a href="javascript:;" data-repeater-create class="btn btn-light-primary">
                                    <i class="fas fa-plus fs-3"></i>
                                    Agregar
                                </a>
                            </div>
                            <!--end::Form group-->
                        </div>
                        <!--end::Repeater-->
                    </div>
                </div>
            </div>
        </div>
        <div class="">
            <div class="text-center mb-2">
                <h3 class="text-gijac">Descripción</h3>
                <span>Habla de tu empresa a los clientes</span>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="fv-row mb-3">
                        <textarea name="description" id="" cols="100" rows="4" class="form-control descripcionPerfil" data-kt-autosize="true"
                            maxlength="512" placeholder="Descripción">{{ $dato?->description ?? '' }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-4">
        <section class="tvf2evcx oq44ahr5 lb5m6g5c s9fl9ege">
            <div
                class="gsqs0kct oauresqk efgp0a3n h3bz2vby g0rxnol2 tvf2evcx oq44ahr5 lb5m6g5c mpdn4nr2 lkjmyc96 gaujl5hk bcymb0na aiput80m e8k79tju">
                <div class="lhggkp7q qq0sjtgm tkdu00h0 ln8gz9je lgqobh8t gfz4du6o r7fjleex qhhg16yg r0y3w5wp"></div>
                <div
                    class="tvf2evcx m0h2a7mj lb5m6g5c j7l1k36l ktfrpxia nu7pwgvd p357zi0d dnb887gk gjuq5ydh i2cterl7 ac2vgrno sap93d0t r15c9g6i g0rxnol2 c6fxfv2p cf33ckg6 sjyhwr5o qxlgi08w lvp0u6in pz0xruzv k1jo73ug isfiuinm">
                    <div
                        class="d1poss59 gyj32ejw cmcp1to6 eg0col54 is0s2vyk bqpwlx4t j5tcbkoq hx27gwwv ig3kka7n a57u14ck a4bg1r4i h1a3x9ys nf75xe5g pvwszkuq a985gkl7 pp0mj9gn">
                        <div role="button" class="g0rxnol2 g9p5wyxn i0tg5vk9 aoogvgrq o2zu3hjb"
                            style="height: 152px; width: 152px; cursor: pointer;">
                            <img src="{{$dato?->profile_picture_url ?? asset('img/logo_mini.png')}}" alt="" draggable="false" tabindex="-1"
                                class="g0rxnol2 imgPerfil f804f6gw ln8gz9je ppled2lx gfz4du6o r7fjleex g9p5wyxn i0tg5vk9 aoogvgrq o2zu3hjb jpthtbts lyqpd7li bs7a17vp csshhazd _11JPr"
                                style="visibility: visible;"/>
                        </div>
                    </div>
                </div>
                <div class="qfejxiq4">
                    <div class="tt8xd2xn jnwc1y2a ngycyvoj svoq16ka">
                        <div class="iqrewfee sy6s5v3r tt8xd2xn jnwc1y2a or9x5nie svoq16ka">
                            <div class="Mk0Bp _30scZ"><span dir="auto" aria-label=""
                                    class="l7jjieqr cw3vfol9 _11JPr selectable-text copyable-text"
                                    style="min-height: 0px;">{{ $datosNumero && isset($datosNumero['data']) ? $datosNumero['data'][0]['verified_name'] : 'GIJAC MESSAGE BUSINESS'}}</span></div>
                        </div>
                    </div>
                    <div class="tt8xd2xn jnwc1y2a brac1wpa svoq16ka f8jlpxt4 sbs3osm6 jgi8eev7">{{ $dato?->vertical ? $categorias[$dato?->vertical] : 'N/A'}}</div>
                </div>
            </div>
            <div class="gsqs0kct oauresqk efgp0a3n h3bz2vby g0rxnol2 tvf2evcx oq44ahr5 lb5m6g5c mpdn4nr2 lkjmyc96 tvsr5v2h bcymb0na jd93c9cp e8k79tju">
                <div class="">
                    <div class="enbbiyaj erpdyial tviruh8d"><a rel="noopener noreferrer" class="bsaq4yhm edeob0r2"
                            href="mailto:{{$dato?->email ?? 'soporte@gijac.co'}}" target="_blank">{{$dato?->email ?? 'soporte@gijac.co'}}</a>
                    </div>
                </div>
                @if (isset($dato?->websites) && count($dato?->websites))
                    <div class="lxsc1wef">
                        @foreach ($dato->websites as $web)
                            <div class="enbbiyaj erpdyial tviruh8d">
                                <div><a rel="noopener noreferrer" class="bsaq4yhm edeob0r2"
                                        href="{{$web}}"
                                        target="_blank">{{$web}}</a></div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <div
                class="gsqs0kct oauresqk efgp0a3n h3bz2vby g0rxnol2 tvf2evcx oq44ahr5 lb5m6g5c brac1wpa lkjmyc96 i4pc7asj bcymb0na przvwfww e8k79tju">
                <div class="i5tg98hk f9ovudaz przvwfww gx1rr48f or9x5nie">
                    <div class="p357zi0d gndfcl4n">
                        <div class="mx771qyo gfz4du6o r7fjleex g0rxnol2 lhj4utae le5p0ye3">
                            <span class="bze30y65 a4ywakfo k06jqncy e1gr2w1z" aria-label="">
                                Info. y número de teléfono
                            </span>
                        </div>
                    </div>
                </div>
                <div tabindex="0" class="gx1rr48f">
                    <div class="_2vQWV p357zi0d gndfcl4n bvcnfjzh f9ovudaz cc8mgx9x">
                        <div class="ggj6brxn m0h2a7mj lb5m6g5c kv6wexeh gfz4du6o r7fjleex lhj4utae hmy10g0s hc2u0oym myel2vfb">
                            <span class="fe5nidar fs7pz031 tl2vja3b e1gr2w1z" aria-label="">
                                <span dir="auto" title="¡Hola! Estoy usando WhatsApp." aria-label=""
                                    class="cw3vfol9 _11JPr selectable-text copyable-text"
                                    style="min-height: 0px;">
                                    {{ $dato?->about ?? '' }}
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
                <div tabindex="0" class="gx1rr48f _3VUan">
                    <div class="_2vQWV p357zi0d gndfcl4n k45dudtp f9ovudaz cc8mgx9x">
                        <div class="ggj6brxn m0h2a7mj lb5m6g5c kv6wexeh gfz4du6o r7fjleex lhj4utae le5p0ye3">
                            <span dir="auto" class="_11JPr selectable-text copyable-text">
                                <span class="fe5nidar fs7pz031 tl2vja3b e1gr2w1z" aria-label="">
                                    {{formatoTelefono($numeroG)}}
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
