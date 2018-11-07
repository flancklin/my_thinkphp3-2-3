
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
M = {
    baseHost: "http://" + location.host,
    version: "2015062600178",
    toJSON: function(a) {
        return JSON.stringify(a)
    },
    json: function(a) {
        return JSON.parse(a)
    },
    loadCss: function(a) {
        var b = document.createElement("link");
        b.setAttribute("rel", "stylesheet"),
            b.setAttribute("type", "text/css"),
            b.setAttribute("href", a),
            document.getElementsByTagName("head")[0].appendChild(b)
    },
    loadScript: function(a, b) {
        var c = document.createElement("script");
        c.readyState ? c.onreadystatechange = function() {
            ("loaded" == c.readyState || "complete" == c.readyState) && (c.onreadystatechange = null, b && b())
        } : c.onload = function() {
            b && b()
        },
            c.src = a.indexOf("?") > 0 ? a + "&ver=" + M.version : a + "?ver=" + M.version;
        var d = document.getElementsByTagName("script")[0];
        d.parentNode.insertBefore(c, d)
    },
    urlQuery: function(a) {
        var b = location.search;
        b = b.replace(/#[^&]*$/, "");
        var c = b.indexOf(a + "=");
        if (-1 != c) {
            var d = b.substr(c),
                e = new Array;
            return e = -1 == d.indexOf("&") ? d.split("=") : d.substr(0, d.indexOf("&")).split("="),
                e[1]
        }
        return ""
    },
    post: function(a, b, c, d) {
        var e = new Date,
            f = e.getTime() + "_" + Math.random().toString().substr(2),
            g = "post_" + f;
        window[g] = function(a) {
            window[g] = void 0,
                c(a),
                i.removeChild(k),
                i.removeChild(j)
        };
        var h = document,
            i = h.body,
            j = h.createElement("iframe");
        j.style.display = "none",
            j.name = "post_iframe",
            j.src = "about:blank",
            i.appendChild(j);
        var k = h.createElement("form");
        k.action = a,
            k.method = "post",
            k.target = "post_iframe",
            k.style.display = "none";
        var l = h.createElement("textarea");
        l.name = "param",
            l.value = b,
            k.appendChild(l);
        var m = h.createElement("textarea");
        if (m.name = "callback", m.value = g, k.appendChild(m), d) {
            var n = h.createElement("textarea");
            n.name = "redirect_url",
                n.value = d,
                k.appendChild(n)
        } else {
            var o = h.createElement("textarea");
            o.name = "callbackURL",
                o.value = M.baseHost + "/others/post_callback.html",
                k.appendChild(o)
        }
        i.appendChild(k),
            k.submit()
    },
    orignPost: function(a, b, c) {
        var d = new XMLHttpRequest;
        d.open("POST", a, !0),
            d.setRequestHeader("Content-type", "application/x-www-form-urlencoded"),
            d.onreadystatechange = function() {
                4 == d.readyState && 200 == d.status && c(M.json(d.responseText))
            },
            d.send("param=" + b)
    },
    jsonp: function(a, b, c) {
        var d = new Date,
            e = d.getTime() + "_" + Math.random().toString().substr(2),
            f = "jsonpcallback_" + e,
            g = "interval_" + e;
        window[f] = function(a) {
            window[f] = void 0,
            b && b(a)
        },
            window[g] = setInterval(function() {
                    new Date - d > 8e3 && (clearInterval(window[g]), c && c())
                },
                100);
        var h = a + (-1 == a.indexOf("?") ? "?callback=" : "&callback=") + f;
        window.callbackArr ? window.callbackArr.push(h) : (window.callbackArr = [], window.callbackArr.push(h)),
            M.loadScript(h,
                function() {
                    clearInterval(window[g])
                })
    },
    ajax: function(a) {
        function b() {
            d.abort(),
            a.error && a.error()
        }
        var c = function(b) {
            "UDC" !== a.part || a.complate ? a.complate && a.complate(b) : 0 === Number(b.status.status_code) ? (console.log("请求ok"), a.success && a.success(b)) : (console.log("请求失败= " + b.status.status_reason), a.error ? a.error(b) : M._alert(b.status.status_reason))
        };
        if ("jsonp" === a.type) M.jsonp(a.url,
            function(a) {
                c(a)
            });
        else if ("get" === a.type) {
            var d = new XMLHttpRequest;
            d.open("GET", a.url, !0),
                d.send(),
                d.onreadystatechange = function() {
                    if (4 == d.readyState) switch (d.status) {
                        case 200:
                            a.success && a.success(M.json(d.responseText));
                            break;
                        case 404:
                            console.log("404--URL地址未找到"),
                                b();
                            break;
                        case 500:
                            console.log("500--服务器错误"),
                                b()
                    }
                }
        } else if ("post" === a.type) {
            var e = new XMLHttpRequest;
            e.open("POST", a.url, !0),
                e.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8"),
                e.onreadystatechange = function() {
                    4 != e.readyState || 200 != e.status && 304 != e.status || a.complate(e.responseText)
                },
                e.send(a.param)
        }
    },
    get: function(a, b) {
        function c() {
            d.abort()
        }
        var d = new XMLHttpRequest;
        d.open("GET", a, !0),
            d.send(),
            window.getAJAXVariable = d,
            d.onreadystatechange = function() {
                if (4 == d.readyState) switch (d.status) {
                    case 200:
                        b(M.json(d.responseText));
                        break;
                    case 404:
                        console.log("404--URL地址未找到"),
                            c();
                        break;
                    case 500:
                        console.log("500--服务器错误"),
                            c()
                }
            }
    },
    abortAJAX: function(a) {
        "get" == a.methond.toLowerCase() && (window.postAJAXVariable && window.postAJAXVariable.abort(), window.getAJAXVariable && window.getAJAXVariable.abort())
    },
    getCookie: function(a) {
        var b = document.cookie.indexOf(a + "="),
            c = document.cookie.indexOf(";", b);
        return -1 == b ? "" : unescape(document.cookie.substring(b + a.length + 1, c > b ? c : document.cookie.length))
    },
    setCookie: function(a, b, c) {
        var d = new Date;
        d.setTime(d.getTime() + 2592e6);
        var e = "; path=/" + (-1 != document.domain.indexOf("vdian.com") ? "; domain=vdian.com" : -1 != document.domain.indexOf("koudai.com") ? "; domain=koudai.com" : -1 != document.domain.indexOf("weidian.com") ? "; domain=weidian.com" : "");
        if ("object" == typeof a)
            for (var f in a) {
                var g = escape(f) + "=" + escape(a[f]);
                document.cookie = g + "; expires=" + d.toGMTString() + e
            } else {
            var g = escape(a) + "=" + escape(b);
            document.cookie = g + (c ? "" : "; expires=" + d.toGMTString()) + e
        }
    },
    delCookie: function(a) {
        var b = "; path=/" + (-1 != document.domain.indexOf("vdian.com") ? "; domain=vdian.com" : -1 != document.domain.indexOf("koudai.com") ? "; domain=koudai.com" : -1 != document.domain.indexOf("weidian.com") ? "; domain=weidian.com" : "");
        document.cookie = escape(a) + "=; expires=" + new Date(0).toUTCString() + b
    },
    clearCookie: function() {
        var a = document.cookie.match(/[^ =;]+(?=\=)/g);
        if (a)
            for (var b = a.length,
                     c = b; c--;) M.delCookie(a[c])
    },
    ua: function() {
        return navigator.userAgent.toLowerCase()
    },
    isMobile: function() {
        return M.ua().match(/iPhone|iPad|iPod|Android|IEMobile/i)
    },
    isAndroid: function() {
        return -1 != M.ua().indexOf("android") ? 1 : 0
    },
    isIOS: function() {
        var a = M.ua();
        return -1 != a.indexOf("iphone") || -1 != a.indexOf("ipad") || -1 != a.indexOf("ipod") ? 1 : 0
    },
    platform: function() {
        return M.isMobile() ? M.isIOS() ? "IOS" : M.isAndroid() ? "Android" : "other-mobile" : "PC"
    },
    isWeixin: function() {
        return -1 != M.ua().indexOf("micromessenger") ? 1 : 0
    },
    isWeixinPay: function() {
        if (M.isWeixin()) {
            var a = M.ua(),
                b = a.substr(a.indexOf("micromessenger"), 18).split("/");
            return Number(b[1]) >= 5 ? 1 : 0
        }
        return 0
    },
    isLogin:function(){
        //M.loadScript('/Public/scripts/login_pp.js');
        if(!M.getCache('memberID')){
            //location.href="/VCrowd/Weixin/login/url/"+base64encode(location.href);
            bindingPhone();return false;
        }else
            return 1;
    },
    _alert: function(a, b, c) {
        function d(a) {
            c ? b && b() : setTimeout(function() {
                    a.fadeOut(function() {
                        a.parent().fadeOut(function() {
                            $(this).remove()
                        }),
                        b && b()
                    })
                },
                1500)
        }
        if ($("#_alert_bg").length) $("#_alert_content").html(a),
            d($("#_alert_content"));
        else {
            var e = window.top.document,
                f = e.createElement("div");
            f.setAttribute("id", "_alert_bg"),
                e.body.appendChild(f);
            var g = e.createElement("div");
            g.setAttribute("id", "_alert_content"),
                f.appendChild(g),
                $(g).html(a).fadeIn(function() {
                    d($(this))
                })
        }
    },
    _remove_alert: function(a) {
        $("#_alert_bg").length ? $("#_alert_bg").fadeOut(function() {
            $(this).remove(),
            a && a()
        }) : a && a()
    },
    _confirm: function(a, b, c, d, e) {
        $("#_confirm_bg").length && $("#_confirm_bg").remove();
        var f = document,
            g = f.createElement("div");
        g.setAttribute("id", "_confirm_bg"),
            f.body.appendChild(g);
        var h = f.createElement("div");
        h.setAttribute("id", "_confirm_content"),
            g.appendChild(h);
        var i = $("#_confirm_content"),
            j = "";
        j = j + "<div id='_confirm_text'>" + a + "</div>",
            j += "<div id='_confirm_btnW'>",
            c && c[0] ? (j = j + "<div id='_confirm_btnA' class='" + b[1] + "'>" + b[0] + "</div>", j = j + "<div id='_confirm_btnB' class='" + c[1] + "'>" + c[0] + "</div>") : j = j + "<div id='_confirm_btnA' class='" + b[1] + "' style='width:100%;border-right:none'>" + b[0] + "</div>",
            j += "</div>",
            i.html(j).fadeIn(),
            $("#_confirm_btnA").bind("click",
                function() {
                    d && d(),
                        i.fadeOut(),
                        $("#_confirm_bg").fadeOut(function() {
                            $(this).remove()
                        })
                }),
        c && c[0] && $("#_confirm_btnB").bind("click",
            function() {
                e && e(),
                    i.fadeOut(),
                    $("#_confirm_bg").fadeOut(function() {
                        $(this).remove()
                    })
            })
    },gin: function() {
        var b = document,
            c = b.body;
        $("#floatDiv_closeWrap,#tool_bar_bg").fadeOut(),
            E.iframe.remove(),
            c.style.height = "auto";
    },
    extend: function(a) {
        "object" == typeof E ? a && a() : M.loadScript("/scripts/extend.js",
            function() {
                a && a()
            })
    },
    setCache: function(k, v) {
        localStorage.setItem(k, v);
    },
    getCache: function(k) {
        var val = localStorage.getItem(k);
        return val ? val : '';
    },
    setCacheJson: function(k, v) {
        M.setCache(k, M.toJSON(v));
    },
    getCacheJson: function(k) {
        var val = localStorage.getItem(k);
        return val ? M.json(val) : '';
    },
    clearCache: function(k) {
        if (k) localStorage.removeItem(k);
        else localStorage.clear();
    }
}
var uid = cache.get('memberID');
var token='';
function toast(msg){
	layer.open({content:msg,skin:"msg",time:2});
}

function setRedirectUrl(url,jumpUrl){
	cache.set('redirectUrl',url);
	location.href=jumpUrl;
}
function redirectUrl(){
	var ua = navigator.userAgent.toLowerCase();	
	if(/iphone|ipad|ipod/.test(ua)){
		var url=cache.get('redirectUrl');
		if(url==""){
			location.href="/";
		}else{
			cache.clear('redirectUrl');
			location.href=url;
		}
	}else if(/android/.test(ua)){
		history.back();	
	}
}

function getUrlPath(){
    var url = document.location.toString();
    var arrUrl = url.split("//");

    var start = arrUrl[1].indexOf("/");
    var relUrl = arrUrl[1].substring(start);//stop省略，截取从start开始到结尾的所有字符

    if(relUrl.indexOf("?") != -1){
        relUrl = relUrl.split("?")[0];
    }
    relUrl=relUrl.substr(1);
    return relUrl.replace(/\//g,"_");
}

function base64encode(str){
    var out,i,len,base64EncodeChars="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
    var c1,c2,c3;
    len=str.length;
    i=0;
    out="";
    while(i<len){
        c1=str.charCodeAt(i++)&0xff;
        if(i==len){
            out+=base64EncodeChars.charAt(c1>>2);
            out+=base64EncodeChars.charAt((c1&0x3)<<4);
            out+="==";
            break;
        }
        c2=str.charCodeAt(i++);
        if(i==len){
            out+=base64EncodeChars.charAt(c1>>2);
            out+=base64EncodeChars.charAt(((c1&0x3)<<4)|((c2&0xF0)>>4));
            out+=base64EncodeChars.charAt((c2&0xF)<<2);
            out+="=";
            break;
        }
        c3=str.charCodeAt(i++);
        out+=base64EncodeChars.charAt(c1>>2);
        out+=base64EncodeChars.charAt(((c1&0x3)<<4)|((c2&0xF0)>>4));
        out+=base64EncodeChars.charAt(((c2&0xF)<<2)|((c3&0xC0)>>6));
        out+=base64EncodeChars.charAt(c3&0x3F);
    }
    return out;
}
function getQueryString(name) {
    var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if(r!=null)return  unescape(r[2]); return "";
}


