"use strict";
var KTModalTwoFactorAuthentication = function() {
    var e, t, o, n, i, a, r, s, l, d, c, u, m, f;
    window.p = function() {
        o.classList.remove("d-none"),
        i.classList.add("d-none"),
        d.classList.add("d-none")
    };
    return {
        init: function() {
            (e = document.querySelector("#kt_modal_two_factor_authentication")) && (t = new bootstrap.Modal(e),
            o = e.querySelector('[data-kt-element="options"]'),
            n = e.querySelector('[data-kt-element="options-select"]'),
            i = e.querySelector('[data-kt-element="sms"]'),
            a = e.querySelector('[data-kt-element="sms-form"]'),
            r = e.querySelector('[data-kt-element="sms-submit"]'),
            s = e.querySelector('[data-kt-element="sms-cancel"]'),
            d = e.querySelector('[data-kt-element="apps"]'),
            c = e.querySelector('[data-kt-element="apps-form"]'),
            u = e.querySelector('[data-kt-element="apps-submit"]'),
            m = e.querySelector('[data-kt-element="apps-cancel"]'),
            n.addEventListener("click", (function(e) {
                e.preventDefault();
                var t = o.querySelector('[name="auth_option"]:checked');
                o.classList.add("d-none"),
                "sms" == t.value ? i.classList.remove("d-none") : d.classList.remove("d-none")
            }
            )),
            l = FormValidation.formValidation(a, {
                fields: {
                    telefono: {
                        validators: {
                            notEmpty: {
                                message: "El telefono es requerido"
                            }
                        }
                    }
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger,
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: ".fv-row",
                        eleInvalidClass: "",
                        eleValidClass: ""
                    })
                }
            }),
            r.addEventListener("click", (function(e) {
                e.preventDefault(),
                l && l.validate().then((function(e) {
                    "Valid" == e ? enviarDatos('#formVerificacionSms', r, t, 2) : Swal.fire({
                        text: "Lo sentimos, parece que se han detectado algunos errores. Inténtalo de nuevo.",
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok",
                        customClass: {
                            confirmButton: "btn btn-success"
                        }
                    })
                }
                ))
            }
            )),
            s.addEventListener("click", (function(e) {
                e.preventDefault(),
                o.querySelector('[name="auth_option"]:checked'),
                o.classList.remove("d-none"),
                i.classList.add("d-none")
            }
            )),
            f = FormValidation.formValidation(c, {
                fields: {
                    code: {
                        validators: {
                            notEmpty: {
                                message: "El código es requerido"
                            }
                        }
                    }
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger,
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: ".fv-row",
                        eleInvalidClass: "",
                        eleValidClass: ""
                    })
                }
            }),
            u.addEventListener("click", (function(e) {
                e.preventDefault(),
                f && f.validate().then((function(e) {
                    "Valid" == e ? enviarDatos('#formVerificacionApps', u, t, 1) : Swal.fire({
                        text: "Lo sentimos, parece que se han detectado algunos errores. Inténtalo de nuevo.",
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok",
                        customClass: {
                            confirmButton: "btn btn-success"
                        }
                    })
                }
                ))
            }
            )),
            m.addEventListener("click", (function(e) {
                e.preventDefault(),
                o.querySelector('[name="auth_option"]:checked'),
                o.classList.remove("d-none"),
                d.classList.add("d-none")
            }
            )))
        }
    }
}();
KTUtil.onDOMContentLoaded((function() {
    KTModalTwoFactorAuthentication.init()
}
));


const enviarDatos = (form, u, t, tipo) => {
    u.setAttribute("data-kt-indicator", "on");
    u.disabled = !0;
    let formData = new FormData(document.querySelector(form));
    formData.append('tipo', tipo);
    if (tipo == 3) {
        let inputTelefono = generalidades.darTelefonoInput(`${form} #telefonoWhatsapp`);
        let tel = inputTelefono?.getNumber(intlTelInputUtils.numberFormat.NATIONAL);
        tel = tel.replace(/\((\w+)\)/g, "$1");
        tel = tel.replace(/-/g, "");
        tel = tel.replace(/\s/g, "");
        let codigo = inputTelefono?.getSelectedCountryData()?.dialCode ?? '';
        let pais_tel = inputTelefono?.getSelectedCountryData()?.iso2 ?? '';
        formData.set('telefono', tel);
        formData.set('codigo_tel', codigo);
        formData.set('pais_tel', pais_tel);
    }
    
    const config = {
        'method': 'POST',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
        'body': formData
    }

    const success = (response) => {
        if (response.estado == 'success') {
            generalidades.ocultarValidaciones(form);
            u.removeAttribute("data-kt-indicator");
            u.disabled = !1;
            Swal.fire({
                text: response?.mensaje,
                icon: "success",
                buttonsStyling: !1,
                confirmButtonText: "Ok",
                customClass: {
                    confirmButton: "btn btn-success"
                }
            }).then((function(e) {
                e.isConfirmed && (t.hide(), p())
            }))
            t.hide(); 
            p();
            location.reload();
        }
        generalidades.ocultarCargando(form);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    const error = (response) => {
        generalidades.ocultarCargando(form);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
        generalidades.mostrarValidaciones(form, response.validaciones);
    }
    const ruta = route("verify2FA");
    generalidades.create(ruta, config, success, error);
    generalidades.mostrarCargando(form);
}