var wwwurl='http://www.quansousuo.net/';
var apiurl='http://api.quansousuo.net/';
var wxurl='http://wx.quansousuo.net/';
var adminurl='http://admin.quansousuo.net/';
var staticurl='http://admin.quansousuo.net/Tpl/Admin/static/';
var staticuploadurl=staticurl+'upload/';
//http://admin.quansousuo.net/Tpl/Admin/static/upload/image/
api = {
	toast : function(v){
		alert(v.msg);
	}
};

cache = {
	set : function(k,v){
		localStorage.setItem(k,v);
	},
	get : function(k){
		return localStorage.getItem(k);
	},
	clear : function(k){
		if(k){
			return localStorage.removeItem(k);
		}else{
			window.localStorage.clear();
			return true;
		}
	}
};

cacheJson = {
	set : function(k,v){
		//localStorage.setItem(k,v);
		if(v) cache.set(k,JSON.stringify(v));
	},
	get : function(k){
		var ret = [];
		var str = cache.get(k);
		if(str) ret=JSON.parse(str);
		return ret;
	}
};

function fucCheckLength(strTemp) {//判断中文字符串长度
	var i, sum;
	sum = 0;
	for (i = 0; i < strTemp.length; i++) {
		if ((strTemp.charCodeAt(i) >= 0) && (strTemp.charCodeAt(i) <= 255))
			sum = sum + 1;
		else
			sum = sum + 2;
	}
	return sum;
}

function toJson(str){
	str=str.substr(2);
	str=str.substr(0,str.length-1);
	return JSON.parse(str);
}

function openAPP(name,url){//第三方网页
	cache.set("modulename",name);
	cache.set("moduleurl",url);
	api.openWin({
	       name: "module",
	       url: "../module/main_cont.html"
	});
}

/*时间戳转换*/
Date.prototype.pattern=function(fmt) {      
    var o = {      
    "M+" : this.getMonth()+1, //月份      
    "d+" : this.getDate(), //日      
    "h+" : this.getHours()%12 == 0 ? 12 : this.getHours()%12, //小时      
    "H+" : this.getHours(), //小时      
    "m+" : this.getMinutes(), //分      
    "s+" : this.getSeconds(), //秒      
    "q+" : Math.floor((this.getMonth()+3)/3), //季度      
    "S" : this.getMilliseconds() //毫秒      
    };      
    var week = {      
    "0" : "\u65e5",      
    "1" : "\u4e00",      
    "2" : "\u4e8c",      
    "3" : "\u4e09",      
    "4" : "\u56db",      
    "5" : "\u4e94",      
    "6" : "\u516d"     
    };      
    if(/(y+)/.test(fmt)){      
        fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));      
    }      
    if(/(E+)/.test(fmt)){      
        fmt=fmt.replace(RegExp.$1, ((RegExp.$1.length>1) ? (RegExp.$1.length>2 ? "\u661f\u671f" : "\u5468") : "")+week[this.getDay()+""]);      
    }      
    for(var k in o){      
        if(new RegExp("("+ k +")").test(fmt)){      
            fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));      
        }      
    }      
    return fmt;      
}

//制保留2位小数，如：2，会在2后面补上00.即2.00    
function toDecimal2(x) {    
	var f = parseFloat(x);    
	if (isNaN(f)) {    
		return false;    
	}    
	var f = Math.round(x*100)/100;    
	var s = f.toString();    
	var rs = s.indexOf('.');    
	if (rs < 0) {    
		rs = s.length;    
		s += '.';    
	}    
	while (s.length <= rs + 2) {    
		s += '0';    
	}    
	return s;    
}

//删除JSON元素
Array.prototype.remove=function(dx){
	if(isNaN(dx)||dx>this.length){
		return false;
	}
	for(var i=0,n=0;i<this.length;i++){
		if(this[i]!=this[dx]){
			this[n++]=this[i];
		}
	}
	this.length-=1;
}

function isArray(arr){ 
	return Object.prototype.toString.call(arr) === "[object Array]"; 
}

function nofind() {
    var img = event.srcElement;
    img.src = "/static/images/img_logo.png";
    img.onerror = null;
}


//登陆绑定

//登陆组件
//使用方法：
//var login = new common_logins();
//login.login({
//	defSubmitFn: function () {
//		alert("defSubmitFn");
//	}, registerBtnFn: function () {
//		alert("registerBtnFn");
//	}, findPasswordBtnFn: function () {
//		alert("findPasswordBtnFn");
//	}, getCodeFn: function () {
//		alert("getCodeFn");
//	}, fastSubmitFn: function () {
//		alert("fastSubmitFn");
//	}, footTextFn: function () {
//		alert("footTextFn");
//	}
//});

window.loaders = {
    ui:function(type) {
        var loaders_type = "";
        if(type=="ball-pulse") {
            loaders_type = '<div class="loader-inner ball-pulse"><div></div><div></div><div></div></div>';
        }else if(type=="ball-clip-rotate") {
            loaders_type = '<div class="loader-inner ball-clip-rotate"><div></div></div>';
        }
        var html = $('<main id="loaders" class="loaded"><div class="loaders"><div class="loader">'+loaders_type+'</div></div></main>');
        html.appendTo($("body"));
    },
    show:function(type) {
        this.hide();
        if(!type && typeof type !== "string") {
            alert("需要传入字符串");
            return false;
        }
        this.ui(type);
    },
    hide:function() {
        $("loaded").removeClass("loaded");
        $("#loaders").remove();
    }
}


function common_logins() {
    this.cfg = {
        width: 500,                     //宽度
        height: 300,                     //高度
        boxClass: "win_dialog",        //集体样式前缀
        closeFn: null,                  //点击右上方关闭回调函数

        defLoginName: "普通登陆",
        defLoginFn: null,

        fastLoginName: "快捷登陆",
        fastLoginFn: null,

        submitBtnName: "登陆",                  //提交名称
        defSubmitFn: null,
        fastSubmitFn: null,                      //提交回调函数


        getCodeName: "获取验证码",            //获取验证码
        getCodeFn: null,                     //获取验证码回调
        phoneInputPlaceholder: "请输入手机号码",
        codeInputPlaceholder: "输入验证码",
        registerBtnName: "注册账号",           //注册账号
        registerBtnFn: null,                    //注册账号回调
        findPasswordBtnName: "密码找回",          //密码找回
        findPasswordBtnFn: null,
        footText: "已阅读并同意《旺铺注册使用协议》",
        footTextFn: null,

        commonLoginCodeFn: null,
        commonLoginFn: null
    };
    this.boudingBox = null;//窗口容器
    this.returnFns = {};//回调函数集合
}
common_logins.prototype = $.extend({}, {
    on: function (type, returnFn) {
        //新建一个装载函数
        if (typeof this.returnFns[type] == 'undefined') {
            this.returnFns[type] = [];
        }
        //这个装载对象载入方法,并去除重复方法
        this.returnFns[type].push(returnFn);
        this.returnFns[type] = unique(this.returnFns[type]);
        function unique(arr) {
            //去除重复元素
            var result = [], hash = {};
            for (var i = 0, elem; (elem = arr[i]) != null; i++) {
                if (!hash[elem]) {
                    result.push(elem);
                    hash[elem] = true;
                }
            }
            return result;
        }

        return this;
    },
    fire: function (type, data) {
        //验证对象类型
        if (this.returnFns[type] instanceof Array) {
            var Fns = this.returnFns[type];
            for (var i = 0; i < Fns.length; i++) {
                //运行数组中所有存在的方法，data是一个方法的参数
                Fns[i](data);
            }
        }
    },
    clean: function () {
        for (var item in this.returnFns) {
            delete this.returnFns[item];
        }
    },
    renderUI: function (winType) {
        switch (winType) {
            case "defLogin":
            {
                var defLoginHTML = "<ul><li><input class='defLogin_phoneInput' type='text' placeholder='" + this.cfg.phoneInputPlaceholder + "'></li>" +
                    "<li><input class='defLogin_passwordInput' type='text' placeholder='" + this.cfg.phoneInputPlaceholder + "'></li>" +
                    "<li><button class='defLogin_submit' type='submit'>" + this.cfg.submitBtnName + "</button></li>" +
                    "<li><button class='registerBtn'>" + this.cfg.registerBtnName + "</button><button class='findPasswordBtn'>" + this.cfg.findPasswordBtnName + "</button></li></ul>";
                this.cfg.content = defLoginHTML;
            }
                break;
            case "fastLogin" :
            {

                var fastLoginHTML = "<ul><li><input class='fastLogin_phoneInput' type = 'text' placeholder='" + this.cfg.phoneInputPlaceholder + "'></li>" +
                    "<li><input class='fastLogin_codeInput' type = 'text' placeholder='" + this.cfg.codeInputPlaceholder + "'><button class='fastLogin_codeBtn' type='submit'>" + this.cfg.getCodeName + "</button></li>" +
                    "<li><button class='fastLogin_submit'>" + this.cfg.submitBtnName + "</button></li>" +
                    "<li><button class='registerBtn'>" + this.cfg.registerBtnName + "</button></li></ul>";
                this.cfg.content = fastLoginHTML;
            }
                break;
            case "commonLogin":
            {
                var html = '<ul><li><input class="commonLogin_phone_input" type="text" placeholder="请输入手机号码" onfocus="inputfocus()" onblur="inputblur()"></li>' +
                    '<li><input class="commonLogin_code_input" type="text" placeholder="请输入验证码" onfocus="inputfocus()" onblur="inputblur()"><button class="commonLogin_codeBtn" type="button">获取验证码</button></li>' +
                    '<li><button class="commonLogin_submit" type="submit">绑定</button></li></ul>';
                this.cfg.content = html;
            }
                break;
        }
        if (winType == "commonLogin") {
            this.cfg.title = "绑定手机"
        } else {
            this.cfg.title = "<button class='defLoginBtn'>" + this.cfg.defLoginName + "</button><button class='fastLoginBtn'>" + this.cfg.fastLoginName + "</button>";
        }
        var box = $("<div class='" + this.cfg.boxClass + "'></div>");
        var box_head = $("<div class='head'>" + this.cfg.title + "</div>");
        var box_body = $("<div class='body'>" + this.cfg.content + "</div>");
        var box_foot = $("<div class='foot'>" + this.cfg.footText + "</div>");
        var close = $("<div class='close'></div>");
        var mask = $("<div class='win_mask'></div>");
        mask.appendTo($(document.body));
        if (winType != "commonLogin") {
            box.append(box_foot);
        }
        box.append(box_head);
        box.append(box_body);
        close.appendTo(box);
        this.boudingBox = box;
    },
    bindUI: function () {
        var that = this;
        $(".win_mask").on("click", function () {
            that.destroy();
        });
        this.boudingBox && this.boudingBox.delegate(".defLogin_submit", "click", function (e) {
            that.fire("defSubmitFn");
            that.destroy();
        }).delegate(".close", "click", function (e) {
            that.fire("closeFn");
            that.destroy();
        })
            .delegate(".registerBtn", "click", function (e) {
                that.fire("registerBtnFn");
            })
            .delegate(".findPasswordBtn", "click", function (e) {
                that.fire("findPasswordBtnFn");
            })
            .delegate(".fastLogin_codeBtn", "click", function (e) {
                that.fire("getCodeFn");
            })
            .delegate(".fastLogin_submit", "click", function (e) {
                that.fire("fastSubmitFn");
            }).delegate(".foot", "click", function (e) {
                that.fire("footTextFn");
            }).delegate(".defLoginBtn", "click", function (e) {
                that.destroy();
                that.render("defLogin");
            }).delegate(".fastLoginBtn", "click", function (e) {
                that.destroy();
                that.render("fastLogin");
            }).delegate(".commonLogin_codeBtn", "click", function (e) {
            	var data = {
                    phone: $(".commonLogin_phone_input").val(),
                    smsbtn:this
                };
                that.fire("commonLoginCodeFn", data);
            })
            .delegate(".commonLogin_submit", "click", function (e) {
                var data = {
                    phone: $(".commonLogin_phone_input").val(),
                    code: $(".commonLogin_code_input").val()
                };
                that.fire("commonLoginFn", data);
            });
        this.cfg.closeFn && this.on("closeFn", this.cfg.closeFn);
        this.cfg.defSubmitFn && this.on("defSubmitFn", this.cfg.defSubmitFn);
        this.cfg.registerBtnFn && this.on("registerBtnFn", this.cfg.registerBtnFn);
        this.cfg.findPasswordBtnFn && this.on("findPasswordBtnFn", this.cfg.findPasswordBtnFn);
        this.cfg.getCodeFn && this.on("getCodeFn", this.cfg.getCodeFn);
        this.cfg.fastSubmitFn && this.on("fastSubmitFn", this.cfg.fastSubmitFn);
        this.cfg.footTextFn && this.on("footTextFn", this.cfg.footTextFn);
        this.cfg.commonLoginCodeFn && this.on("commonLoginCodeFn", this.cfg.commonLoginCodeFn);
        this.cfg.commonLoginFn && this.on("commonLoginFn", this.cfg.commonLoginFn);
    },
    syncUI: function () {
        this.boudingBox.css({
            width: this.cfg.width + 'px',
            height: this.cfg.height + 'px',
            top: (this.cfg.top || (window.innerHeight - this.cfg.height) / 2) + 'px',
            left: (this.cfg.left || (window.innerWidth - this.cfg.width) / 2) + 'px'
        });
    },
    render: function (option) {
        if ($('.' + this.cfg.boxClass).length) {
            return false;
        }
        this.clean();
        this.renderUI(option);
        this.bindUI();
        this.syncUI();
        this.boudingBox.appendTo($(document.body));
    },
    commonLogin: function (cfg) {
        $.extend(this.cfg, cfg);
        this.render("commonLogin");
    },
    login: function (cfg) {
        $.extend(this.cfg, cfg);
        this.render("defLogin");
    },
    destroy: function () {
        $(".win_mask").off();
        $(".win_mask").remove();
        this.boudingBox.off();
        this.boudingBox.remove();
    }
})
function inputfocus(){//安卓点文本框输入时，底部要浮动问题    先隐藏
	$(".footer-fixed-container").hide();
}
function inputblur(){
	$(".footer-fixed-container").show();
}


