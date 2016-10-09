<script type="text/javascript">
var regex_mobile = <? $regex = Conf('regex'); echo $regex['mobile'];?>;
</script>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" id="myModalLabel"><?=$title;?></h4>
</div>

<div class="modal-body">
    <form role="form" class="form-horizontal" action="<?=$form_action;?>" method="post" id="player_form">
        <div class="row">
            <div class="alert alert-danger col-lg-5 col-lg-offset-2" id="error" hidden></div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label" for="name">姓名</label>
            <div class="col-lg-2">
                <input type="text" class="form-control" name="name" value="<?=htmlspecialchars($info['name']);?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label" for="number">编号</label>
            <div class="col-lg-2">
                <input type="text" class="form-control" name="number" value="<?=$info['number'];?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label" for="mobile">手机号</label>
            <div class="col-lg-2">
                <input type="text" class="form-control" name="mobile" value="<?=$info['mobile'];?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label" for="status">状态</label>
            <div class="col-lg-2">
            <? foreach($status_arr as $v): ?>
                <label class="radio-inline">
                    <input type="radio" name="status" value="<?=$v['status'];?>" <?=$info['status'] == $v['status'] ? 'checked' : ''; ?>><?=$v['str'];?>
                </label>
            <? endforeach; ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label" for="cover">选手封面</label>
            <div class="col-lg-5">
                <input type="hidden" name="cover" value="<?=$info['cover'];?>">
                <div class="row" style="margin-bottom:10px;">
                    <div class="col-lg-3">
                        <span class="btn btn-success fileinput-button">
                            <i class="icon-plus icon-white"></i>
                            <span>选择图片</span>
                            <input type="file" name="files" class="fileupload" fileinputname="cover">
                        </span>
                    </div>
                    <div class="col-lg-9">
                        <div class="progress" style="margin-bottom:0; display:none;">
                            <div class="progress-bar progress-bar-success" style=""></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 show">
                    <? if($info['cover']): ?>
                        <img src="<?=UPLOAD_URL.'/'.$info['cover'];?>">
                    <? endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label" for="desc">选手相册</label>
            <div class="col-lg-8">
                <div class="row" style="margin-bottom:10px;">
                    <div class="col-lg-1">
                        <span class="btn btn-success fileinput-button">
                            <i class="icon-plus icon-white"></i>
                            <span>选择图片</span>
                            <input type="file" name="files" multiple class="fileupload" fileinputname="gallery">
                        </span>
                    </div>
                    <div class="col-lg-9">
                        <div class="progress" style="margin-bottom:0; display:none;">
                            <div class="progress-bar progress-bar-success" style=""></div>
                        </div>
                    </div>
                </div>

                <div class="row show">
                    <? foreach($info['gallery'] as $img): ?>
                    <div class="col-lg-2">
                        <img src="<?=UPLOAD_URL.'/'.$img;?>">
                        <input type="hidden" name="gallery[]" value="<?=$img;?>">
                        <button class="btn btn-danger delete">删除</button>
                    </div>
                    <? endforeach; ?>
                </div>

            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label" for="desc">选手介绍</label>
            <div class="col-lg-8">
                <textarea class="form-control" rows="8" name="desc"><?=htmlspecialchars($info['desc']);?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label" for="submit"></label>
            <div class="col-lg-4">
                <input type="submit" class="btn btn-primary" name="submit" value="提交">
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
$(document).ready(function(){
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
                    var fileinputname = $(this).attr('fileinputname');
                    var show = $(this).parents('.form-group').find('.show');
                    if(fileinputname == 'cover'){
                        $(show).html('<img src="'+ret.data.full_url+'">');
                        $('input[name=cover]').val(ret.data.db_url);
                        $('input[name=cover]').trigger('input');
                    }else if(fileinputname == 'gallery'){
                        var div_img = '<div class="col-lg-2"><img src="'+ret.data.full_url+'" />' + '<input type="hidden" name="gallery[]" value="'+ret.data.db_url+'" /><button class="btn btn-danger delete">删除</button></div>';
                        $(show).append(div_img);
                        $(show).find('.delete').on('click', function(){
                            $(this).parent().remove();
                        });
                    }
                    
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

    $('.show .delete').on('click', function(){
        $(this).parent().remove();
    });

    $("#player_form").bootstrapValidator({
        excluded: [':disabled'],
        submitButtons: '',
        submitHandler: function(validator, form, submitButton){
            $.post(form.attr('action'), form.serialize(), function(ret){
                if(ret.code == 1){
                    $('#myModal').modal('hide');
                    dt_player_list.ajax.reload( null, false );
                }else if(ret.redirect){
                    window.location.href = ret.redirect;
                }else{
                    $('#error').text(ret.msg).show();
                    $("html,body").animate({scrollTop: 0}, 100);
                }
            }, 'json');
        },
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: "不能为空"
                    }
                }
            },
            number: {
                validators: {
                    notEmpty: {
                        message: "不能为空"
                    },
                    regexp: {
                        regexp: /^[1-9]\d*$/,
                        message: '请填写大于0正整数',
                    },
                }
            },
            /*mobile: {
                validators: {
                    notEmpty: {
                        message: "不能为空"
                    },
                    regexp: {
                        regexp: regex_mobile,
                        message: '请填写正确手机号',
                    },
                }
            },*/
            cover: {
                validators: {
                    notEmpty: {
                        message: "不能为空"
                    },
                }
            },
            desc: {
                validators: {
                    notEmpty: {
                        message: "不能为空"
                    }
                }
            },
        }
    });
    
});
</script>
