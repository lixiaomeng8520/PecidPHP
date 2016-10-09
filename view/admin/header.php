<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>大河网投票管理系统</title>

    <link href="<?=PUBLIC_ADMIN_URL;?>/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=PUBLIC_ADMIN_URL;?>/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <link href="<?=PUBLIC_ADMIN_URL;?>/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="http://cdn.bootcss.com/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css">
    <link href="<?=PUBLIC_ADMIN_URL;?>/bower_components/bootstrapvalidator/dist/css/bootstrapValidator.min.css" rel="stylesheet">
    <link href="<?=PUBLIC_ADMIN_URL;?>/bower_components/jQuery-File-Upload/css/jquery.fileupload.css" rel="stylesheet">


    <link href="<?=PUBLIC_ADMIN_URL;?>/dist/css/sb-admin-2.css" rel="stylesheet">
    <link href="<?=PUBLIC_ADMIN_URL;?>/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="<?=PUBLIC_ADMIN_URL;?>/vote/vote.css?1" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript">
        var site_url = "<?=SITE_URL;?>";
        var upload_url = "<?=U('Index', 'upload');?>";
        var act_list_url = "<?=U('Act', 'actList');?>";
        var current_url = "<?=CURRENT_URL;?>";
        var regex = <?=json_encode(Conf('regex'))?>;//alert(regex.require);
    </script>
</head>

<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html">大河网投票管理系统</a>
            </div>

            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="<?=U('Index', 'logout');?>"><i class="fa fa-sign-out fa-fw"></i> 登出</a></li>
                    </ul>
                </li>
            </ul>

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li>
                            <a href="<?=U('Index', 'index');?>" class="<?=CONTROLLER == 'Index' ? 'active' : '';?>"><i class="fa fa-dashboard fa-fw"></i> 首页</a>
                        </li>
                        <li>
                            <a href="<?=U('Act', 'actList');?>" class="<?=CONTROLLER == 'Act' ? 'active' : '';?>"><i class="fa fa-trophy fa-fw"></i> 活动</a>
                        </li>
                        <li>
                            <a href="<?=U('Log', 'index');?>" class="<?=CONTROLLER == 'Log' ? 'active' : '';?>"><i class="fa fa-clock-o fa-fw"></i> 日志</a>
                        </li>
                    </ul>
                </div>
            </div>
            
        </nav>

        <div id="page-wrapper">