    <? include 'header.php';?>

    <title>大河网投票系统</title>
    <script src="<?=PUBLIC_INDEX_URL;?>/js/jquery.min.js" type="text/javascript"></script>
    <script src="<?=PUBLIC_INDEX_URL;?>/js/echo.min.js" type="text/javascript"></script>
    <script src="http://qzonestyle.gtimg.cn/qzone/qzact/common/share/share.js" type="text/javascript"></script>

</head>
<body>
<article class="clearfix">
	<header class="clearfix">
		<!-- <img src="img/img2.png" /> -->
	</header>
</article>
<section class="listBox mt-10 ml-10 mr-10 clearfix" id="act_list">

</section>
<footer class="mt-30">
	<p>大河网版权所有</p>
</footer>

<script type="text/javascript">
// 懒加载
// Echo.init({  offset:120,throttle: 0 });
// 判断图片宽度
// $(function(){
// 	var n = $(".listBox img").length;
// 	for(var i=0; i<n;i++){
// 		var imgW = $(".listBox img").eq(i).width();
// 		$(".listBox img").eq(i).height(imgW);
// 	}
// });

// 分页加载
    var page = 0;
    var has_more = 1;
    var get_act_list = function(){
        if(has_more == 1){
            page++;
            $.post('<?=CURRENT_URL;?>', {page: page}, function(data){
                has_more = data.data.has_more;
                $('#act_list').append(data.data.html);
            }, 'json');
        }

    };


    $(window).scroll(function(){
        var totalheight = parseFloat($(window).height()) + parseFloat($(window).scrollTop());
        if ($(document).height() == totalheight){
            get_act_list();
        }
    });
    get_act_list();

    setShareInfo({
        title: "大河网微投票",
        summary: "来为你喜欢的选手投票吧",
        pic: "<?=PUBLIC_URL.'/index/img/logo.png';?>",
        url: "<?=CURRENT_URL;?>",
        WXconfig: {
            swapTitleInWX: false,
            appId: '<?= $sign_package["appid"]; ?>',
            timestamp: <?= $sign_package["timestamp"]; ?>,
            nonceStr: '<?= $sign_package["nonceStr"]; ?>',
            signature: '<?= $sign_package["signature"]; ?>'
        }
    });
</script>
</body>
</html>
