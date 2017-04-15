<?php 
return array(
	'data_dic' => array(
		'log_type'	=>	array(
			array(
				'type'	=>	1,
				'str'	=>	'登录',
				'action'=>	'Index.login',
			),
			array(
				'type'	=>	2,
				'str'	=>	'添加管理员',
				'action'=>	'Admin.adminAdd',
			),
			array(
				'type'	=>	3,
				'str'	=>	'编辑管理员',
				'action'=>	'Admin.adminEdit',
			),
			array(
				'type'	=>	4,
				'str'	=>	'添加活动',
				'action'=>	'Act.actAdd',
			),
			array(
				'type'	=>	5,
				'str'	=>	'编辑活动',
				'action'=>	'Act.actEdit',
			),
			array(
				'type'	=>	6,
				'str'	=>	'添加选手',
				'action'=>	'Act.playerAdd',
			),
			array(
				'type'	=>	7,
				'str'	=>	'编辑选手',
				'action'=>	'Act.playerEdit',
			),
		),
		'act_status'	=>	array(
			array(
				'status'	=>	1,
				'str'		=>	'正常',
			),
			array(
				'status'	=>	2,
				'str'		=>	'暂停',
			),
			// array(
			// 	'status'	=>	3,
			// 	'str'		=>	'结束',
			// ),
		),
		'player_status'	=>	array(
			array(
				'status'	=>	1,
				'str'		=>	'正常',
			),
			array(
				'status'	=>	2,
				'str'		=>	'冻结',
			),
		),
	),

	'weixin'	=>	array(
		'appid'		=>	'wx90cb4d32ca1105a2',
		'appsecret'	=>	'e7fbb9966d7875f0aed0cb1d1ba1581e',
	),

	'rewrite'	=>	array(
		'index'	=>	array(
			array(
				'from'	=>	array(
					'controller'	=>	'Index',
					'action'		=>	'act',
					'query'			=>	array('aid'),
				),
				'to'	=>	'act/[aid]',
			),
			array(
				'from'	=>	array(
					'controller'	=>	'Index',
					'action'		=>	'player',
					'query'			=>	array('pid'),
				),
				'to'	=>	'player/[pid]',
			),
		),
	),

	'Upload'	=>	array(
		'upload_dir'		=>	'upload',
		'allow_extension'		=>	array('jpg'),
		'allow_mine_type'	=>	array('image/jpg', 'image/jpeg'),
		'max_size'			=>	2000000, // byte; 2mb
		'file_name'			=>	'imgFile',
	),

	'db_driver'	=>	'Mysql',
);
?>