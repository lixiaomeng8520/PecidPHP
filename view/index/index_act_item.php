<? foreach($act_list as $v): ?>
	<a href="<?=U('Index', 'act', array('aid'=>$v['aid']));?>" class="list" style="display:block;">
		<div class='listimg'><img data-echo="<?=PUBLIC_INDEX_URL;?>/img/loading.gif" src="<?=thumb_url($v['banner']);?>"></div>
		<div class="listcon">
			<p><?=$v['title'];?></p>
			<p><?=format_date($v['vote_start']);?> ~ <?=format_date($v['vote_end']);?></p>
		</div>
	</a>
<? endforeach; ?>