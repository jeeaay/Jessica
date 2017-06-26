/* liming Shangwutong function
 * auto invatation
 * by Hito Yang
 */
if(!openZoosUrl){
	function openZoosUrl(){
		var url=window.location.href || "Client not Support Location";
		var url=escape(url);
		var chaturl='http://live.zoosnet.net/LR/Chatpre.aspx?id=LEK72929338&cid=null&lng=en&sid=null&p='+url;
		window.open(chaturl,'chatwin','height=600,width=800,top=100,left=200,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no');
	}
}
function invatation(note,interval){
	if (typeof(note) === "number"){
		var interval = note;
		var note = 'We provide online service, if you have any questions, please contact us!';
	}else if(typeof(note) === "string"){
		var note = note;
		var interval = interval || 5;
	}else{
		var note = 'We provide online service, if you have any questions, please contact us!';
		var interval  = 5;
	}
	var chatframe = document.createElement('div');
	chatframe.innerHTML='<p style="font-size:12px;color:#000;font-weight:normal;position:absolute;top:35px;left:140px;right:10px">'+note+'</p><span style="position:absolute;top:135px;left:188px;width:80px;height:24px;cursor:pointer;font-size:12px;display:block;font-weight:bold;line-height:24px;text-align:center;border:1px solid #88c0f9;background:#e2efff;border-radius:3px" onclick="openZoosUrl();document.getElementById(\'onlinechatframe\').style.display=\'none\';">Chat now</span><span style="position:absolute;top:135px;right:20px;width:80px;height:24px;cursor:pointer;font-size:12px;display:block;font-weight:bold;line-height:24px;text-align:center;border:1px solid #88c0f9;background:#e2efff;border-radius:3px" onclick="document.body.removeChild(this.parentNode);invatation(\''+note+'\','+(interval*2.2)+');">Chat Later</span>';
	chatframe.style.width="396px";
	chatframe.id="onlinechatframe";
	chatframe.style.height="187px";
	chatframe.style.background="url('/default/img/swt.jpg')";
	chatframe.style.fontWeight="bold";
	chatframe.style.position="fixed";
	chatframe.style.left= (document.body.clientWidth-396)/2 + "px";
	chatframe.style.top = (window.screen.availHeight-300)/2 + "px";
	if(!document.getElementById('onlinechatframe') && !document.getElementById('traywinright_1217')){
		interval = interval * 1000;
		setTimeout(function(){
			document.body.appendChild(chatframe);
		},interval)
	}
}
