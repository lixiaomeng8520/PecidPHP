<!-- <div class="row">
    <div class="col-lg-12">
        <h3 class="page-header"><?=$title;?></h3>
    </div>
</div> -->
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" id="myModalLabel"><?=$title;?></h4>
</div>

<div class="modal-body">
    <form role="form" class="form-horizontal" action="<?=$form_action;?>" method="post" id="act_form">
        <div class="row">
            <div class="alert alert-danger col-lg-5 col-lg-offset-2" id="error" hidden></div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label">活动名</label>
            <div class="col-lg-4">
                <input type="text" class="form-control" name="title" value="<?=htmlspecialchars($info['title']);?>" required data-bv-notempty-message="不能为空">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label">分享摘要</label>
            <div class="col-lg-5">
                <textarea class="form-control" rows="4" name="share_summary"><?=htmlspecialchars($info['share_summary']);?></textarea>
            </div>
        </div>
        <div class="form-group" id="div_share_pic">
            <label class="col-lg-3 control-label">分享图片</label>
            <div class="col-lg-5">
                <input type="hidden" name="share_pic" value="<?=$info['share_pic'];?>" required data-bv-notempty-message="不能为空">
                <div class="row" style="margin-bottom:10px;">
                    <div class="col-lg-3">
                        <div class="btn btn-success fileinput-button">
                            <i class="glyphicon glyphicon-plus"></i>
                            <span>选择文件</span>
                            <input class="fileupload" type="file" name="files">
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="progress" style="margin-bottom:0; display:none;">
                            <div class="progress-bar progress-bar-success" style=""></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 show">
                        <? if($info['share_pic']): ?>
                        <img src="<?=UPLOAD_URL.'/'.$info['share_pic'];?>">
                        <? endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label">状态</label>
            <div class="col-lg-4">
                <? foreach($status_arr as $v): ?>
                <label class="radio-inline">
                    <input type="radio" name="status" value="<?=$v['status'];?>" <?=$info['status'] == $v['status'] ? 'checked' : ''; ?>><?=$v['str'];?>
                </label>
            <? endforeach; ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label">投票开始时间</label>
            <div class="col-lg-3">
                <div class="input-group date">
                    <input type="text" class="form-control" name="vote_start" value="<?=$info['vote_start'];?>" Readonly>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label">投票结束时间</label>
            <div class="col-lg-3">
                <div class="input-group date">
                    <input type="text" class="form-control" name="vote_end" value="<?=$info['vote_end'];?>" Readonly>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label">投票间隔时间（小时）</label>
            <div class="col-lg-1">
                <input type="text" class="form-control" name="vote_interval" value="<?=$info['vote_interval'];?>" required data-bv-notempty-message="不能为空" pattern="^[1-9]\d*|0$" data-bv-regexp-message="请填正整数">
            </div>
        </div>
        <div class="form-group" id="div_banner">
            <label class="col-lg-3 control-label">banner图</label>
            <div class="col-lg-5">
                <input type="hidden" name="banner" value="<?=$info['banner'];?>" required data-bv-notempty-message="不能为空">
                <div class="row" style="margin-bottom:10px;">
                    <div class="col-lg-3">
                        <div class="btn btn-success fileinput-button">
                            <i class="glyphicon glyphicon-plus"></i>
                            <span>选择文件</span>
                            <input class="fileupload" type="file" name="files">
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="progress" style="margin-bottom:0; display:none;">
                            <div class="progress-bar progress-bar-success" style=""></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 show">
                        <? if($info['banner']): ?>
                        <img src="<?=UPLOAD_URL.'/'.$info['banner'];?>">
                        <? endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label">活动规则</label>
            <div class="col-lg-8">
                <textarea class="form-control" rows="8" name="desc" required data-bv-notempty-message="不能为空"><?=htmlspecialchars($info['desc']);?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"></label>
            <div class="col-lg-4">
                <input type="submit" class="btn btn-primary" name="submit" value="提交">
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
$(document).ready(function(){
    $(".date").datetimepicker({
        locale: 'zh-cn',
        format: 'YYYY-MM-DD HH:mm:ss',
        useCurrent: false,
        sideBySide: true,
        showTodayButton: true,
        ignoreReadonly: true,
    });

    $(".fileupload").fileupload({
        url: upload_url,
        dataType: 'json',
        autoUpload: true,
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        start: function(e) {
            // $('#progress').show();
        },
        done: function (e, data) {
            // $('#progress').hide();
            var ret = data.result;
            if(ret.redirect){
                window.location.href = ret.redirect;
            }else{
                if(ret.code == 1){
                    var form_group = $(this).parents('.form-group');
                    var name = form_group.attr('id').substring(4);

                    form_group.find('.show').html('<img src="'+ret.data.full_url+'">');
                    $('input[name='+name+']').val(ret.data.db_url);
                    $('input[name='+name+']').trigger('input');
                }else{
                    alert(ret.msg);
                }
            }
        },
        progressall: function (e, data) {
            // var progress = parseInt(data.loaded / data.total * 100, 10);
            // $('#progress .progress-bar').css('width',progress + '%');
        }
    }).prop('disabled', !$.support.fileInput).parent().addClass($.support.fileInput ? undefined : 'disabled');


    $('#act_form').bootstrapValidator({
        excluded: [':disabled'],
        submitButtons: '',
        submitHandler: function(validator, form, submitButton){
            $.post(form.attr('action'), form.serialize(), function(ret){
                if(ret.code == 1){
                    $('#myModal').modal('hide');
                    dt_act_list.ajax.reload( null, false );
                }else if(ret.redirect){
                    window.location.href = ret.redirect;
                }else{
                    $('#error').text(ret.msg).show();
                    $("html,body").animate({scrollTop: 0}, 100);
                }
            }, 'json');
        },

    });
});
</script>
