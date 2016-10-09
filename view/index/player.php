	<? include 'header.php'; ?>

	<title><?=$act_info['title'].'-'.$player_info['name'];?></title>
</head>
<body>
<div class="weixin-tip">
	<p>
		<img src="<?=PUBLIC_INDEX_URL;?>/img/live_weixin.png" alt="" style="width:100%"/>
	</p>
</div>
<article class="clearfix top1">
	<header class="clearfix">
		<img src="<?=thumb_url($act_info['banner']);?>" />
	</header>
</article>
<section class="detail clearfix">
	<p><?=$player_info['number'];?>号 <i class="ml-10"><?=$player_info['name'];?></i><span class="fr">票数：<i class="c-red"><?=$player_info['num'];?></i></span></p>
	<div class="clearfix mt-15">
		<hr class="">
		<p class="mt-10 mb-10 h2">个人介绍</p>
		<p><?=str_to_html($player_info['desc']);?></p>
		<a class="voteBtn" href="javascript:void(0)">投TA一票</a>
		<? foreach ($player_info['gallery'] as $img): ?>
			<img src="<?=thumb_url($img);?>">
		<? endforeach; ?>
	</div>
</section>
<section class="detailDes clearfix">
	<h3>活动说明</h3>
	<p><?=str_to_html($act_info['desc']);?></p>
	<h3>活动时间</h3>
	<p><?=format_date($act_info['vote_start']);?>～<?=format_date($act_info['vote_end']);?></p>
</section>
<a class="voteBtn voteBtn2" href="javascript:void(0)">我要投票</a>
<footer class="mt-30 mb-40">
	<p>大河网版权所有</p>
</footer>

<script src="http://qzonestyle.gtimg.cn/qzone/qzact/common/share/share.js" type="text/javascript"></script>
<script src="<?=PUBLIC_INDEX_URL;?>/js/jquery.min.js" type="text/javascript"></script>
<script src="<?=PUBLIC_INDEX_URL;?>/js/layer/layer.js" type="text/javascript"></script>
<script src="<?=PUBLIC_INDEX_URL;?>/js/vote.js?5" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('.voteBtn').click(function(){
		$.post('<?=U('Index', 'vote');?>', {pid: '<?=$player_info['pid'];?>'}, function(data){
			if(data.code == 1){//alert(1);
				show(data.msg, '分享');
				$('.fr i').text(data.data.num);
			}else{
				show(data.msg, '确定');
			}
		}, 'json');
	});

	setShareInfo({
        title: "<?=addslashes($act_info["title"].' - '.$player_info['name']);?>",
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

    $(window).scroll(function(){
			var scroH = $(this).scrollTop();
			var voteBtn = $('.top1').outerHeight();
			if(scroH > (voteBtn*3)){
				$(".voteBtn2").show(800);
			}
			else{
				$(".voteBtn2").css('display','none');
			}
	});
});
</script>
</body>
</html>
