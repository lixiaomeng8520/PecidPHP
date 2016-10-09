	<? include 'header.php';?>
	<title><?=$act_info['title'];?></title>
</head>
<body>
<div class="weixin-tip">
	<p>
		<img src="<?=PUBLIC_INDEX_URL;?>/img/live_weixin.png" alt="" style="width:100%"/>
	</p>
</div>

<article class="clearfix">
	<header class="clearfix">
		<img src="<?=thumb_url($act_info['banner']);?>" />
		<section class="description mt-20">
			<h3>活动说明</h3>
			<p class="hdsm" style="text-indent:0;text-align:justify;"><?=str_to_html($act_info['desc']);?></p>
			<h3>活动时间</h3>
			<p><?=format_date($act_info['vote_start']);?>～<?=format_date($act_info['vote_end']);?></p>
			<!-- <h3>活动规则</h3>
			<p class="hdgz">每个ID每天只能为同一位参赛者投一票</p> -->
		</section>
	</header>
</article>
<div class="wrapper" id="pageCon">
	<ul class="list_box clearfix">

	</ul>
	<div id="loadImg" style="text-align: center; display: none;"><img src="img/loading.gif" style="max-width: 130px; width: 70%;"></div>
</div>

<p style="text-align: center; color: #ccc; display:none" id="all_loaded">加载完毕</p>
<footer class="mt-50">
	<p style="padding-bottom: 0;"><img src="<?=PUBLIC_INDEX_URL.'/img/dhwlogo@1x.png';?>" /></p>
	<p>版权所有</p>
</footer>

<script src="http://qzonestyle.gtimg.cn/qzone/qzact/common/share/share.js" type="text/javascript"></script>
<script src="<?=PUBLIC_INDEX_URL;?>/js/jquery.min.js" type="text/javascript"></script>
<script src="<?=PUBLIC_INDEX_URL;?>/js/layer/layer.js" type="text/javascript"></script>
<script src="<?=PUBLIC_INDEX_URL;?>/js/jquery.masonry.min.js" type="text/javascript"></script>
<script src="<?=PUBLIC_INDEX_URL;?>/js/vote.js?5" type="text/javascript"></script>
<script type="text/javascript">
function masonry(){
	var container = $('#pageCon ul');
    container.imagesLoaded(function(){
    	container.masonry({
       		itemSelector: '.picCon'
    	});
    });
}
$(function(){
	// 分页加载
	var page = 0;
	var has_more = 1;
	var get_player_list = function(){
		if(has_more == 1){
			page++;
			$.post('<?=CURRENT_URL;?>', {page: page}, function(data){
				has_more = data.data.has_more;
				$('.list_box').append(data.data.html);
				masonry();
				$('#pageCon ul').masonry('reload');
				if(has_more == 0){
					$('#all_loaded').show();
				}
			}, 'json');
		}
	};


	$(window).scroll(function(){
 		var totalheight = parseFloat($(window).height()) + parseFloat($(window).scrollTop());
	    if ($(document).height() > totalheight - 100){
	        get_player_list();
	    }
	});
	get_player_list();

	$(document).on('click', '.voteBtn', function(){
		var voteBtn = $(this);
		$.post('<?=U('Index', 'vote');?>', {pid: $(this).attr('pid')}, function(data){
			if(data.code == 1){//alert(1);
				show(data.msg, '分享');
				var i_num = voteBtn.prev().find('i');
				i_num.text(parseInt(i_num.text()) + 1); 
			}else{
				show(data.msg, '确定');
			}
		}, 'json');
	});

	setShareInfo({
        title: "<?=addslashes($act_info['title']);?>",
        summary: "<?=addslashes($act_info['share_summary']);?>",
        pic: "<?=thumb_url($act_info['share_pic']);?>",
        url: "<?=CURRENT_URL;?>",
        WXconfig: {
            swapTitleInWX: false,
            appId: '<?= $sign_package["appid"]; ?>',
            timestamp: <?= $sign_package["timestamp"]; ?>,
            nonceStr: '<?= $sign_package["nonceStr"]; ?>',
            signature: '<?= $sign_package["signature"]; ?>'
        }
    });
});

</script>
</body>
</html>
