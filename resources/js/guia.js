/**
 * ===================== JS DE PRODUCCIÓN =====================
 * COPIA EXACTA de public/js/onboarding-widget.js. En el prototipo no hay
 * endpoint (data-onb-w-endpoint vacío), por lo que la función refresh()
 * no genera peticiones: la demo pinta los escenarios con el mismo método
 * applyHtml() que usa el widget real al recibir la respuesta AJAX.
 */
(function ($) {
    "use strict";

    var OPEN_KEY = "gi_onb_widget_open";
    var root = document.querySelector("[data-onb-widget]");
    if (!root) {
        return;
    }

    var endpoint = root.getAttribute("data-onb-w-endpoint") || "";
    var cooldownMs =
        (parseInt(root.getAttribute("data-onb-w-cooldown"), 10) || 30) * 1000;

    var isOpen = false;
    var inflight = false;
    var reqToken = 0;
    var lastFetch = 0;
    var lastFocusable = null;

    var $capsuleSlot = $('[data-onb-w="capsule"]', root);
    var $panelSlot = $('[data-onb-w="panel"]', root);

    // Referencia perezosa: los slots se reemplazan con cada refresco AJAX,
    // así que el nodo del panel nunca se cachea.
    function $panelEl() {
        return $("[data-onb-w-panel]", root);
    }

    function setOpen(open, options) {
        var opts = options || {};
        isOpen = open;

        $(root).toggleClass("is-open", open);

        var $toggle = $(root).find("[data-onb-w-toggle]").first();
        if ($toggle.length) {
            $toggle.attr("aria-expanded", open ? "true" : "false");
        }

        try {
            window.sessionStorage.setItem(OPEN_KEY, open ? "1" : "0");
        } catch (e) {
            /* almacenamiento no disponible: continuar */
        }

        var $panel = $panelEl();
        if (open) {
            $panel.attr("tabindex", "-1");
            if (!opts.silent && $panel.length) {
                $panel.trigger("focus");
            }
        } else if (
            opts.restoreFocus &&
            lastFocusable &&
            document.contains(lastFocusable[0])
        ) {
            lastFocusable.trigger("focus");
        }
    }

    function applyHtml(data) {
        if (!(data && data.html)) {
            return;
        }

        var listScroll = $panelEl().find(".onb-w-list").scrollTop();

        if (data.html.capsule) {
            $capsuleSlot.html(data.html.capsule);
        }
        if (data.html.panel) {
            $panelSlot.html(data.html.panel);
        }

        $panelEl().find(".onb-w-list").scrollTop(listScroll);

        $(root)
            .find("[data-onb-w-toggle]")
            .first()
            .attr("aria-expanded", isOpen ? "true" : "false");
    }

    function refresh(force) {
        if (!endpoint || inflight) {
            return;
        }

        if (!force && Date.now() - lastFetch < cooldownMs) {
            return;
        }

        inflight = true;
        var token = ++reqToken;
        $(root).addClass("is-refreshing");

        $.getJSON(endpoint)
            .done(function (data) {
                if (token !== reqToken) {
                    return;
                }
                lastFetch = Date.now();
                applyHtml(data);
            })
            .fail(function (xhr) {
                if (xhr && xhr.status === 401) {
                    window.location.href = "/login";
                }
            })
            .always(function () {
                inflight = false;
                $(root).removeClass("is-refreshing");
            });
    }

    function maybeRefresh(force) {
        refresh(force === true);
    }

    $(document).on("click", "[data-onb-w-toggle]", function () {
        lastFocusable = $(this);
        setOpen(!isOpen);
        if (isOpen) {
            maybeRefresh(false);
        }
    });

    $(document).on("click", "[data-onb-w-close]", function () {
        setOpen(false, { restoreFocus: true });
    });

    $(document).on("keydown", function (event) {
        if (event.key === "Escape" && isOpen) {
            setOpen(false, { restoreFocus: true });
        }
    });

    $(document).on("mousedown", function (event) {
        if (isOpen && !$(event.target).closest(root).length) {
            setOpen(false);
        }
    });

    $(document).on("click", "[data-onb-w-nav]", function () {
        setOpen(false, { silent: true });
    });

    $(document).on("show.bs.modal show.bs.offcanvas", function () {
        $(root).addClass("is-suppressed");
    });

    $(document).on("hidden.bs.modal hidden.bs.offcanvas", function () {
        $(root).removeClass("is-suppressed");
    });

    document.addEventListener("visibilitychange", function () {
        if (document.visibilityState === "visible") {
            maybeRefresh(false);
        }
    });

    window.addEventListener("pageshow", function (event) {
        if (event.persisted) {
            maybeRefresh(false);
        }
    });

    $(document).on("onboarding:refresh", function () {
        maybeRefresh(true);
    });

    var remembered = "0";
    try {
        remembered = window.sessionStorage.getItem(OPEN_KEY) || "0";
    } catch (e) {
        /* noop */
    }
    setOpen(remembered === "1", { silent: true });

    window.GIJAC = window.GIJAC || {};
    window.GIJAC.onboardingWidget = {
        refresh: function () {
            maybeRefresh(true);
        },
        close: function () {
            setOpen(false);
        },
    };
})(jQuery);
