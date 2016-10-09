<? include VIEW_PATH.'/admin/header.php';?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">活动列表</h1>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <a href="javascript:void(0)" url="<?=U('Act', 'actAdd');?>" class="btn btn-primary modal_form">新增</a>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="dataTable_wrapper">
                    <table width="100%" class="table table-striped table-bordered table-hover" id="act_list">
                        <thead>
                            <tr>
                                <th>活动名</th>
                                <th>状态</th>
                                <th>投票开始时间</th>
                                <th>投票结束时间</th>
                                <th>投票间隔时间</th>
                                <th>参赛人数</th>
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