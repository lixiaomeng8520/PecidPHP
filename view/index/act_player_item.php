<? foreach ($player_list as $v): ?>
<li class="picCon">
	<div class="clearfix">
		<i class="number"><?=$v['number'];?>号</i>
		<a href="<?=U('Index', 'player', array('pid'=>$v['pid']));?>"><img src="<?=thumb_url($v['cover'], 320);?>"></a>
		<p class="clearfix">
			<span class="fl"><?=$v['name'];?></span>
			<span class="fr"><i><?=$v['num'];?></i><small>票</small></span>
		</p>
		<a href="javascript:void(0)" class="voteBtn" pid="<?=$v['pid'];?>">投TA一票</a>
	</div>
</li>
<? endforeach; ?>
