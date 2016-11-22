/*
 * jQuery Dropdown: A simple dropdown plugin
 *
 * Contribute: https://github.com/claviska/jquery-dropdown
 *
 * @license: MIT license: http://opensource.org/licenses/MIT
 *
 */
jQuery && function($) {
    function t(t, e) {
        var n = t ? $(this) : e,
            d = $(n.attr("data-jq-dropdown")),
            a = n.hasClass("jq-dropdown-open");
        if (t) {
            if ($(t.target).hasClass("jq-dropdown-ignore")) return;
            t.preventDefault(), t.stopPropagation()
        } else if (n !== e.target && $(e.target).hasClass("jq-dropdown-ignore")) return;
        o(), a || n.hasClass("jq-dropdown-disabled") || (n.addClass("jq-dropdown-open"), d.data("jq-dropdown-trigger", n).show(), r(), d.trigger("show", {
            jqDropdown: d,
            trigger: n
        }))
    }

    function o(t) {
        var o = t ? $(t.target).parents().addBack() : null;
        if (o && o.is(".jq-dropdown")) {
            if (!o.is(".jq-dropdown-menu")) return;
            if (!o.is("A")) return
        }
        $(document).find(".jq-dropdown:visible").each(function() {
            var t = $(this);
            t.hide().removeData("jq-dropdown-trigger").trigger("hide", {
                jqDropdown: t
            })
        }), $(document).find(".jq-dropdown-open").removeClass("jq-dropdown-open")
    }

    function r() {
        var t = $(".jq-dropdown:visible").eq(0),
            o = t.data("jq-dropdown-trigger"),
            r = o ? parseInt(o.attr("data-horizontal-offset") || 0, 10) : null,
            e = o ? parseInt(o.attr("data-vertical-offset") || 0, 10) : null;
        0 !== t.length && o && t.css(t.hasClass("jq-dropdown-relative") ? {
            left: t.hasClass("jq-dropdown-anchor-right") ? o.position().left - (t.outerWidth(!0) - o.outerWidth(!0)) - parseInt(o.css("margin-right"), 10) + r : o.position().left + parseInt(o.css("margin-left"), 10) + r,
            top: o.position().top + o.outerHeight(!0) - parseInt(o.css("margin-top"), 10) + e
        } : {
            left: t.hasClass("jq-dropdown-anchor-right") ? o.offset().left - (t.outerWidth() - o.outerWidth()) + r : o.offset().left + r,
            top:/* o.offset().top + o.outerHeight() + e*/ o.offset().top - 120 + e
        })
    }
    $.extend($.fn, {
        jqDropdown: function(r, e) {
            switch (r) {
                case "show":
                    return t(null, $(this)), $(this);
                case "hide":
                    return o(), $(this);
                case "attach":
                    return $(this).attr("data-jq-dropdown", e);
                case "detach":
                    return o(), $(this).removeAttr("data-jq-dropdown");
                case "disable":
                    return $(this).addClass("jq-dropdown-disabled");
                case "enable":
                    return o(), $(this).removeClass("jq-dropdown-disabled")
            }
        }
    }), $(document).on("click.jq-dropdown", "[data-jq-dropdown]", t), $(document).on("click.jq-dropdown", o), $(window).on("resize", r)
}(jQuery);