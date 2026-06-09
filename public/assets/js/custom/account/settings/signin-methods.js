"use strict";

const kt_signin_change_email = '#kt_signin_change_email';

var KTAccountSettingsSigninMethods = function () {
    var t, e, n, o, i, s, r, a, l;
    window.d = function () {
        e.classList.toggle("d-none"),
            s.classList.toggle("d-none"),
            n.classList.toggle("d-none")
    };
    window.c = function () {
        o.classList.toggle("d-none"),
            a.classList.toggle("d-none"),
            i.classList.toggle("d-none")
    };
    return {
        init: function () {
            var m;
            t = document.getElementById("kt_signin_change_email"),
                e = document.getElementById("kt_signin_email"),
                n = document.getElementById("kt_signin_email_edit"),
                o = document.getElementById("kt_signin_password"),
                i = document.getElementById("kt_signin_password_edit"),
                s = document.getElementById("kt_signin_email_button"),
                r = document.getElementById("kt_signin_cancel"),
                a = document.getElementById("kt_signin_password_button"),
                l = document.getElementById("kt_password_cancel"),
                e && (s.querySelector("button").addEventListener("click", (function () {
                    d()
                }
                )),
                    r.addEventListener("click", (function () {
                        d()
                    }
                    )),
                    a.querySelector("button").addEventListener("click", (function () {
                        c()
                    }
                    )),
                    l.addEventListener("click", (function () {
                        c()
                    }
                    ))),
                t && (m = FormValidation.formValidation(t, {
                    fields: {
                        emailaddress: {
                            validators: {
                                notEmpty: {
                                    message: "El Email es requerido"
                                },
                                emailAddress: {
                                    message: "El valor no es una dirección de correo electrónico válida."
                                }
                            }
                        },
                        confirmemailpassword: {
                            validators: {
                                notEmpty: {
                                    message: "El Password es requerido"
                                }
                            }
                        }
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger,
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: ".fv-row"
                        })
                    }
                }),
                    t.querySelector("#kt_signin_submit").addEventListener("click", (function (e) {
                        e.preventDefault(),
                            m.validate().then((function (e) {
                                "Valid" == e ? enviarDatosInicioEmail(t, m)
                                    : swal.fire({
                                        text: "Lo sentimos, parece que se han detectado algunos errores. Inténtalo de nuevo.",
                                        icon: "error",
                                        buttonsStyling: !1,
                                        confirmButtonText: "Ok",
                                        customClass: {
                                            confirmButton: "btn font-weight-bold btn-light-primary"
                                        }
                                    })
                            }
                            ))
                    }
                    ))),
                function (t) {
                    var e, n = document.getElementById("kt_signin_change_password");
                    n && (e = FormValidation.formValidation(n, {
                        fields: {
                            currentpassword: {
                                validators: {
                                    notEmpty: {
                                        message: "Se requiere contraseña actual"
                                    }
                                }
                            },
                            newpassword: {
                                validators: {
                                    notEmpty: {
                                        message: "Se requiere nueva contraseña"
                                    }
                                }
                            },
                            confirmpassword: {
                                validators: {
                                    notEmpty: {
                                        message: "Por favor confirme su contraseña"
                                    },
                                    identical: {
                                        compare: function () {
                                            return n.querySelector('[name="newpassword"]').value
                                        },
                                        message: "La contraseña y su confirmación no son la misma."
                                    }
                                }
                            }
                        },
                        plugins: {
                            trigger: new FormValidation.plugins.Trigger,
                            bootstrap: new FormValidation.plugins.Bootstrap5({
                                rowSelector: ".fv-row"
                            })
                        }
                    }),
                        n.querySelector("#kt_password_submit").addEventListener("click", (function (t) {
                            t.preventDefault(),
                                console.log("click"),
                                e.validate().then((function (t) {
                                    "Valid" == t ? enviarDatosInicioContrasena(n, e) : swal.fire({
                                        text: "Lo sentimos, parece que se han detectado algunos errores. Inténtalo de nuevo.",
                                        icon: "error",
                                        buttonsStyling: !1,
                                        confirmButtonText: "Ok",
                                        customClass: {
                                            confirmButton: "btn font-weight-bold btn-light-primary"
                                        }
                                    })
                                }
                                ))
                        }
                        )))
                }()
        }
    }
}();
KTUtil.onDOMContentLoaded((function () {
    KTAccountSettingsSigninMethods.init()
}
));

const enviarDatosInicioEmail = (t, m) => {
    let formData = new FormData(document.getElementById("kt_signin_change_email"));

    const config = {
        'method': 'PUT',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
        'body': formData
    }

    const success = (response) => {
        if (response.estado == 'success') {
            generalidades.ocultarValidaciones(kt_signin_change_email);
            swal.fire({
                text: response?.mensaje,
                icon: "success",
                buttonsStyling: !1,
                confirmButtonText: "Ok",
                customClass: {
                    confirmButton: "btn font-weight-bold btn-light-primary"
                }
            }).then((function () {
                t.reset(),
                m.resetForm(),
                d()
            }))
        }
        generalidades.ocultarCargando(kt_signin_change_email);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    const error = (response) => {
        generalidades.ocultarCargando(kt_signin_change_email);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
        generalidades.mostrarValidaciones(kt_signin_change_email, response.validaciones);
    }
    const rutaActualizar = route("perfil.email", { "usuario": formData.get("id") });
    generalidades.edit(rutaActualizar, config, success, error);
    generalidades.mostrarCargando(kt_signin_change_email);
}

const enviarDatosInicioContrasena = (t, m) => {
    let formData = new FormData(document.getElementById("kt_signin_change_password"));

    const config = {
        'method': 'PUT',
        'headers': {
            'Accept': generalidades.CONTENT_TYPE_JSON,
        },
        'body': formData
    }

    const success = (response) => {
        if (response.estado == 'success') {
            generalidades.ocultarValidaciones(kt_signin_change_email);
            swal.fire({
                text: response?.mensaje,
                icon: "success",
                buttonsStyling: !1,
                confirmButtonText: "Ok",
                customClass: {
                    confirmButton: "btn font-weight-bold btn-light-primary"
                }
            }).then((function () {
                t.reset(),
                m.resetForm(),
                c()
            }))
        }
        generalidades.ocultarCargando(kt_signin_change_email);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
    }

    const error = (response) => {
        generalidades.ocultarCargando(kt_signin_change_email);
        generalidades.toastrGenerico(response?.estado, response?.mensaje);
        generalidades.mostrarValidaciones(kt_signin_change_email, response.validaciones);
    }
    const rutaActualizar = route("perfil.contrasena", { "usuario": formData.get("id") });
    generalidades.edit(rutaActualizar, config, success, error);
    generalidades.mostrarCargando(kt_signin_change_email);
}