$(document).ready(function(){
	function createPopup(imgSrc,tit){
		var t = tit?tit:'请长按图片保存二维码',
			t1 = '此活动仅限微信会员参加';
		var popup = document.createElement('div'),
			popupContent = document.createElement('div'),
			h2 = document.createElement('h2'),
			h4 = document.createElement('h4'),
			div = document.createElement('div'),
			a = document.createElement('a'),
			titText1 = document.createTextNode(t1),
			titText = document.createTextNode(t),
			x = document.createTextNode('X');
		popup.className = 'popup';
		popupContent.className = 'popup-content';
		div.id = 'qrcode';
		a.className = 'popup-btn';
		a.href = 'javascript:;';
		h2.appendChild(titText1);
		h4.appendChild(titText);
		a.appendChild(x);
		popupContent.appendChild(h2);
		popupContent.appendChild(h4);
		popupContent.appendChild(div);
		popupContent.appendChild(a);
		popup.appendChild(popupContent);
		document.body.appendChild(popup);
	}
	createPopup('/Public/VCrowd/images/ewm.jpg');
	$('.det-erweima').on('click',function(){
		$('.popup').addClass('popup-blk');
	});
	$('.popup-btn').on('click',function(){
		$('.popup').removeClass('popup-blk');
	});
})