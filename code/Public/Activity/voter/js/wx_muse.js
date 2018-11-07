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
	isLogin: function() {
		return M.getCache("UID") ? 1 : 0;
    },
	doLogin: function(a) {
		if(M.isWeixin()){
			var storeID = 200008;
			if(storeID>0){
				//弹出绑定手机
				var login = new common_logins();
				login.commonLogin({
					width: 250,
					height: 210,
					commonLoginCodeFn: function (data) {
						if (!(/^1[3|4|5|7|8][0-9]\d{4,8}$/.test(data.phone))) {
							M._alert('手机号码错误');
							return false;
						}
						if($(data.smsbtn).text()!="获取验证码")return false;
						time($(data.smsbtn));
						
						$.ajax({
							type: "POST",
							async: true,
							dataType: "jsonp",
							jsonp: 'callback',
							data: {
								validPhoneNumStr: data.phone,
								storeID:storeID
							},
							url: apiurl + 'index.php?g=Admin&m=Customer&a=SendSms',
							error: function(XmlHttpRequest, textStatus, errorThrown) {
								if (XmlHttpRequest.responseText != "") {
									alert("出现未知错误！");
								}
							},
							success: function(ret) {
								if (ret.successed == 1) {
									$("#safe_code_input").val('').focus();
									M._alert(ret.message+",60秒后可重新发送！");
								} else M._alert(ret.message);
							}
						});
					},
					commonLoginFn: function (data) {
						if(data.phone==""||data.code==""){
							M._alert("请输入正确的手机号和验证码！");
							return false;
						}
						var type=a;
						$.ajax({
							type: "get",
							dataType: "json",
							data: {
								storeID:storeID,
								openid : M.getCookie('openid'+storeID),
								tel:data.phone,
								code:data.code,
								type:type
							},
							url: 'http://wx.quansousuo.net/index.php?g=Vshop&m=Wxstore&a=regandbind',
							error: function(XmlHttpRequest, textStatus, errorThrown) {
								if (XmlHttpRequest.responseText != "") {
									alert("出现未知错误！");
								}
							},
							success: function(ret) {
								if (ret.status == true) {
									user=ret.user;
									obj = {};
									obj.uid = user.id;
									obj.token = user.token;
									M.setCacheJson('loginObj', obj);
									M.setCache('UID', user.id);
									$(".user_tel").text(data.phone);
									$(".asset_balance").text(ret.money+"元");
									login_status=true;//user页面用
									login.destroy();
                                    if(M.getCache('band')){
                                        M.clearCache('band');
                                        M._alert('绑定成功!');
                                        location.href = '/Bar/Index/index/storeID/'+storeID;
                                    }
								}else{
									M._alert(ret.msg);
								}
							}
						});
					},
					getCodeFn: function (){
						//关闭
					}
				});
				return;	
			}
					
		}
	},
	doLogout: function(a) {
		M.delCookie("WD_b_id"),
			M.delCookie("WD_b_wduss"),
			M.delCookie("WD_b_kduss"),
			M.delCookie("WD_s_id"),
			M.delCookie("WD_s_wduss"),
			M.delCookie("defaultAdress"),
			M.delCookie("WD_pay"),
			M.delCookie("WD_NowPay"),
			M.delCookie("WD_unpay_oID"),
			M.delCookie("WD_unpay_oString"),
			M.delCookie("unreadMsg"),
			M.delCookie("cart_inner_item_count"),
			M.delCookie("IM_login_token"),
			a && a()
	},
	closeLogin: function() {
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
storeID = 200008;
if(M.isWeixin()){
	if(0){//cache.get('UID')>0
	}else{
		//验证是否绑定 绑定则生成相应localstorage 有些页面是si
		if(storeID>0){
			$.ajax({
				type: "get",
				dataType: "json",
				data: {
					storeID:storeID,
					openid : M.getCookie('openid'+storeID)
				},
				url:'http://wx.quansousuo.net/index.php?g=Vshop&m=Wxstore&a=getUserByOpenID',
				success: function(ret) {
					if (ret.status == true) {					
						user=ret.user;
						obj = {};
						obj.uid = user.id;
						obj.token = user.token;
						M.setCacheJson('loginObj', obj);
						M.setCache('UID', user.id);	
						$(".userinfo a.db").eq(0).hide();
						$(".userinfo a.db").eq(1).show();
						$(".userinfo a .name").text(obj.name);
						$(".userinfo a .intro").text(obj.mobile);
					}else{
						M.clearCache('loginObj');
						M.clearCache('UID');					
						$(".userinfo a.db").eq(0).show();	
						$(".userinfo a.db").eq(1).hide();
						$(".userinfo a .name").text("点击登录/注册");
						$(".userinfo a .intro").text("未登录用户");
					}
				}
			});
		}
		
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