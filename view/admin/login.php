<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>大河网投票管理系统</title>

    <!-- Bootstrap Core CSS -->
    <link href="<?=PUBLIC_ADMIN_URL?>/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="<?=PUBLIC_ADMIN_URL?>/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?=PUBLIC_ADMIN_URL?>/dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?=PUBLIC_ADMIN_URL?>/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <link href="<?=PUBLIC_ADMIN_URL.'/bower_components/bootstrapvalidator/dist/css/bootstrapValidator.min.css';?>" rel="stylesheet" >

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">登录投票管理后台</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="登录名" name="adminname" type="text" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="密码" name="password" type="password">
                                </div>
                                <!-- <div class="checkbox">
                                    <label>
                                        <input name="remember" type="checkbox" value="Remember Me">Remember Me
                                    </label>
                                </div> -->
                                <!-- Change this to a button or input when using this as a form -->
                                <input type="submit" class="btn btn-lg btn-success btn-block" value="提交">
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="<?=PUBLIC_ADMIN_URL?>/bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?=PUBLIC_ADMIN_URL?>/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?=PUBLIC_ADMIN_URL?>/bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="<?=PUBLIC_ADMIN_URL?>/dist/js/sb-admin-2.js"></script>

    <script src="<?=PUBLIC_ADMIN_URL?>/bower_components/bootstrapvalidator/dist/js/bootstrapValidator.min.js"></script>

    <script type="text/javascript">
        $("form").bootstrapValidator({
            excluded: [':disabled'], //, ':hidden', ':not(:visible)'
            submitButtons: '',
            submitHandler: function(validator, form, submitButton){
                $.post(form.attr('action'), form.serialize(), function(ret){
                    if(ret.redirect){
                        window.location.href = ret.redirect;
                    }else{
                        alert(ret.msg);
                    }
                }, 'json');
            },
            fields: {
                adminname: {
                    validators: {
                        notEmpty: {
                            message: "不能为空"
                        }
                    }
                },
                password: {
                    validators: {
                        notEmpty: {
                            message: "不能为空"
                        },
                    }
                },
            }
        });
    </script>

</body>

</html>
