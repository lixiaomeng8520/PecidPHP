function show(msg, button){
	layer.msg(msg, {
	  	time: 0 //不自动关闭
	  	,btn: [button]
	  	,yes: function(index){
	    	layer.close(index);
	    	if(button == '分享'){
	    		$('.weixin-tip').css('display','block');
				$('.weixin-tip').click(function(){ $(this).css('display','none'); });	
	    	}
	    	/*if(isWeixin&&/(android)/i.test(ua)){
				$('.weixin-tip').css('display','block');
				$('.weixin-tip').click(function(){ $(this).css('display','none'); });
			}else if(isWeixin&&/iphone|ipad|ipod/.test(ua)){
			  	$('.weixin-tip').css('display','block');
			  	$('.weixin-tip').click(function(){ $(this).css('display','none'); });
			}*/

			// wx.showOptionMenu();
	  	}
	});
}

