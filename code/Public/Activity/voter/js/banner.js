(function ($) {
    $.fn.touchMove = function() {
        var windowWidth = $(window).width(),
            windowHeight = $(window).height(),
            size = $(this).children().size(),
            startX ,
            startY,
            XX,
            YY,
            startLeft,
            moveX,
            swipeX,
            swipeY,
            bj,
            bjz,
            translateX;
        windowWidth = windowWidth >= 640 ? 640 : windowWidth;
        $(this).css('width', windowWidth * (size + 1) ).children('a').css('width' ,windowWidth );
        $(this).css('transform', 'translate3d('+-windowWidth+'px,0px,0px)');
        $(this).live('touchstart' ,function(event) {
            var date = new Date();
             time1 = date.getTime();
            startLeft = parseFloat($(this).css('transform').match(/\-?[0-9]+/g)[1]);
            startX = event.targetTouches[0].pageX;
            startY = event.targetTouches[0].pageY;
            swipeX = true;
            swipeY = true;
        });
        $(this).live('touchmove', function(event) {
            translateX = parseFloat($(this).css('transform').match(/\-?[0-9]+/g)[1]);
            XX = event.targetTouches[0].pageX;
            YY = event.targetTouches[0].pageY;
            if ( swipeX  && Math.abs(XX - startX) - Math.abs(YY - startY) > 0  ) {
                $(this).bind('touchmove' , function(event) {
                    event.preventDefault();
                });
                swipeY = false;
                moveX = parseFloat($(this).css('transform').match(/\-?[0-9]+/g)[1]) + event.targetTouches[0].pageX - startX;
                startX = event.targetTouches[0].pageX;
                $(this).css('transform', 'translate3d('+moveX+'px, 0px,0px)');
            } else if ( swipeY && Math.abs(XX - startX) - Math.abs(YY - startY) < 0 ) {
                swipeX = false;
            }
        });
        $(this).live('touchend', function(event) {
            var date = new Date();
            var time2 = date.getTime();
            $(this).unbind('touchmove');
            translateX = parseFloat($(this).css('transform').match(/\-?[0-9]+/g)[1]);
            bj = windowWidth + translateX % windowWidth;
            if ( time2 - time1 >= 300 ) {
                bjz = windowWidth/2;//滑过1/2翻页
                if ( bj <= bjz ) {
                    left = translateX - bj;
                } else {//复原
                    left = translateX + (windowWidth - bj);
                }
            } else {
                bj = Math.abs(translateX % windowWidth);
                bjz = windowWidth/4;//滑过1/4翻页(点滑效果)
                if ( translateX >= startLeft ) {//页面向右滑动
                    if ( bj >= bjz ) {
                        left = translateX + bj;
                    } else {
                        left = translateX -( windowWidth - bj );
                    }
                } else {//页面向左滑动
                    if ( bj >= bjz * 3 ) {
                        left = translateX + bj;
                    } else {
                        left = translateX -(windowWidth - bj);
                        
                    }
                }  
            }
            if ( startLeft < left ) {//向右滑动
                $(this).animate({transform:'translate3d(0px,0px,0px)'},200,"linear",function(){
                index--;
                if ( index === -1  ) {
                    index = size - 1;
                }
                $(this).next().children('a').eq(index).addClass('index-ad-fixed-on').siblings().removeClass('index-ad-fixed-on');
                $(this).css({transform : 'translate3d('+windowWidth/(-1)+'px,0px,0px)'});
                $(this).children('a').last().remove().clone(true).insertBefore($(this).children('a').first());
                }) ; 
            } else if ( startLeft > left ) {//向左滑动
                $(this).animate({transform:'translate3d('+windowWidth*(-2)+'px,0px,0px)'},200,"linear",function(){
                index++;
                if ( index === size  ) {
                    index = 0;
                }
                $(this).next().children('a').eq(index).addClass('index-ad-fixed-on').siblings().removeClass('index-ad-fixed-on');
                $(this).css({transform:'translate3d('+windowWidth/(-1)+'px,0px,0px)'});
                $(this).children('a').first().remove().clone(true).appendTo($(this)); 
                }) ; 
            } else { //复原
                $(this).css('transition' , 'transform 200ms ease-out');
                $(this).css('transform', 'translate3d('+left+'px,0px,0px)');
            }
        });
    };
})($);
(function ($) {
    $.autoPlay = function(obj) {
        var windowWidth = $(window).width(),
        left = parseFloat(obj.css('transform').match(/\-?[0-9]+/g)[1]),
        len = obj.children().size(),
        flag = true;
        windowWidth = windowWidth >= 640 ? 640 : windowWidth;
        function showImg() {
           obj.animate({transform:'translate3d('+windowWidth*(-2)+'px,0px,0px)'},300,"linear",function(){
            index++;
            if ( index === len  ) {
                index = 0;
            }
            obj.next().children('a').eq(index).addClass('index-ad-fixed-on').siblings().removeClass('index-ad-fixed-on');
            obj.css({transform:'translate3d('+windowWidth/(-1)+'px,0px,0px)'});
            obj.children('a').first().remove().clone(true).appendTo(obj); 
            }) ; 
        }
         adTimer = setInterval( function() {
            showImg();
        } , 4000);
    }
})($);
var index = 0;
$('.index-ad-slide').touchMove();
$.autoPlay($('.index-ad-slide'));
$('.index-ad-slide').on('touchstart' , function() {
	clearInterval(adTimer);
}).on('touchend' , function() {
	$.autoPlay($('.index-ad-slide'));
});

if($('.index-ad-slide1').length){
    $('.index-ad-slide1').touchMove();
    $.autoPlay($('.index-ad-slide1'));
    $('.index-ad-slide1').on('touchstart' , function() {
        clearInterval(adTimer);
    }).on('touchend' , function() {
        $.autoPlay($('.index-ad-slide1'));
    });
}

