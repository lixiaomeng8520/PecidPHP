<? include VIEW_PATH.'/admin/header.php';?>
<script type="text/javascript">
var player_list = '<?=U("Act", "playerList", array("aid"=>$aid));?>';
</script>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">选手列表</h1>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <a href="javascript:void(0)" url="<?=U('Act', 'playerAdd', array('aid'=>$aid));?>" class="btn btn-primary modal_form">新增</a>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="dataTable_wrapper">
                    <table width="100%" class="table table-striped table-bordered table-hover" id="player_list">
                        <thead>
                            <tr>
                                <th>编号</th>
                                <th>姓名</th>
                                <th>手机号</th>
                                <th>票数</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<? include VIEW_PATH.'/admin/footer.php';?>