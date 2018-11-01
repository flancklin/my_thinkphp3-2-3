var cache = {
    set: function(e, g) {
        if (window.localStorage) {
            var h = window.localStorage;
            try {
                h.setItem(e, g)
            } catch(f) {
                $.cookie(e, encodeURI(g))
            }
        } else {
            $.cookie(e, encodeURI(g))
        }
    },
    get: function(d) {
        if (window.localStorage) {
            var f = window.localStorage;
            try {
                f = f.getItem(d);
                if (f == null || f == "") {
                    return ! $.cookie(d) ? "": decodeURI($.cookie(d))
                } else {
                    return f
                }
            } catch(e) {
                return ""
            }
        } else {
            return ! $.cookie(d) ? "": decodeURI($.cookie(d))
        }
    },
    clear: function(d) {
        if (window.localStorage) {
            var f = window.localStorage;
            try {
                f.removeItem(d);
                return "";
            } catch(e) {
                return ""
            }
        } else {
            return ""
        }
    }
};