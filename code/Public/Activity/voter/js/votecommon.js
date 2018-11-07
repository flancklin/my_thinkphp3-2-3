var wait = 60; //时间
var uid = '';
var token = '';

M = {
	baseHost: "http://" + location.host,
	version: "2015062600178",
	toJSON: function(a) {
		return JSON.stringify(a)
	},
	json: function(a) {
		return JSON.parse(a)
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

function time(obj) { //o为按钮的对象，p为可选，这里是60秒过后，提示文字的改变
	if (wait == 0) {
		$(obj).text('获取验证码').show();
		wait = 60;
	} else {
		$(obj).text(wait);
		wait--;
		setTimeout(function() {
			time(obj); //循环调用
		}, 1000);
	}
}

function getRTime(e,t){
	var EndTime= new Date(t); //截止时间 
	var NowTime = new Date();
	var t =EndTime.getTime() - NowTime.getTime();
	if(t<0)return false;
	/*var d=Math.floor(t/1000/60/60/24);
	t-=d*(1000*60*60*24);
	var h=Math.floor(t/1000/60/60);
	t-=h*60*60*1000;
	var m=Math.floor(t/1000/60);
	t-=m*60*1000;
	var s=Math.floor(t/1000);*/

	var d=Math.floor(t/1000/60/60/24);
	var h=Math.floor(t/1000/60/60%24);
	var m=Math.floor(t/1000/60%60);
	var s=Math.floor(t/1000%60);

	// document.getElementById("t_d").innerHTML = d + "天";
	// document.getElementById("t_h").innerHTML = h + "时";
	// document.getElementById("t_m").innerHTML = m + "分";
	// document.getElementById("t_s").innerHTML = s + "秒";
	document.getElementById(e).innerHTML=d + "天"+h + "时"+m + "分"+s + "秒";
}