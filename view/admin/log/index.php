<? include VIEW_PATH.'/admin/header.php';?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">日志列表</h1>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="dataTable_wrapper">
                    <table width="100%" class="table table-striped table-bordered table-hover" id="log_list">
                        <thead>
                            <tr>
                                <th>管理员</th>
                                <th>操作</th>
                                <th>访问地址</th>
                                <th>数据</th>
                                <th>ip</th>
                                <th>时间</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<? include VIEW_PATH.'/admin/footer.php';?>