
function createPopup(imgSrc,tit){
	var t = tit?tit:'系统检测到您是新用户，为防止刷票请从公众号进入投票';
	var popup = document.createElement('div'),
		popupContent = document.createElement('div'),
		h4 = document.createElement('h4'),
		img = document.createElement('img'),
		a = document.createElement('a'),
		titText = document.createTextNode(t),
		x = document.createTextNode('X');
	popup.className = 'popup';
	popupContent.className = 'popup-content';
	img.src = imgSrc;
	a.className = 'popup-btn';
	a.href = '###';
	h4.appendChild(titText);
	a.appendChild(x);
	popupContent.appendChild(h4);
	popupContent.appendChild(img);
	popupContent.appendChild(a);
	popup.appendChild(popupContent);
	document.body.appendChild(popup);
	setTimeout(function(){$('.popup').addClass('popup-blk');},300);
	
	$('.popup-btn').on('click',function(){
		$('.popup').removeClass('popup-blk');
	});
}