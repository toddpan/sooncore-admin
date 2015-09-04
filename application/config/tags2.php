<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 标签(系统预定义标签（必选、可选）、自定义标签)
 * @file tag.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */

//0-必选标签1-可选标签 2自定义标签

$config['system_tags'] = array(
	
	//==================必选标签===============//

	'lastname'=>array(
		'pattern'			=>"/^.{1,50}$/u",//1到50个中英文字符或者数字。注意此处实际填写的是姓名或者姓
		'js_pattern'		=>"/^\S{1,50}$/",//1到50个中英文字符或者数字。注意此处实际填写的是姓名
		'tag_type'			=>0,	//0-必选标签1-可选标签 2-自定义标签
		'umsapifield' => 'lastName',//umsAPI对应字段名,因为必须要用lastname
		'enable'			=>true,	//是否启用
		'show'				=>true,   //是否在标签管理页面中显示
		'alias'				=>array(
								'lastname'=>array(
												'lang'=>'en',
												'name'=>'lastname'
											),
								'姓氏'		  =>array(
												'lang'=>'cn',
												'name'=>'姓氏'
											),

		),
	),

	'firstname'=>array(
		'pattern'			=>"/^.{0,50}$/u",//1到50个中英文字符或者数字。,可以为空
		'js_pattern'		=>"/^\S{1,50}$/",//1到50个中英文字符或者数字。,可以为空
		'tag_type'			=>0,	//0-必选标签1-可选标签 2-自定义标签
		'umsapifield' => 'firstName',//umsAPI对应字段名
		'enable'			=>true,	//是否启用
		'show'				=>true,   //是否在标签管理页面中显示
		'alias'				=>array(
								'firstname'=>array(
												'lang'=>'en',
												'name'=>'firstname'
											),
								'名字'			=>array(
												'lang'=>'cn',
												'name'=>'名字'
											),
		),
	),
	
	'displayname'=>array(
			'pattern'			=>"/^.{0,50}$/u",
			'js_pattern'		=>"/^.{0,50}$/",
			'tag_type'			=>0,	   //0-必选标签1-可选标签 2-自定义标签
			'umsapifield' 		=> 'displayName',//umsAPI对应字段名
			'enable'			=>true,	   //是否启用
			'show'				=>true,    //是否在标签管理页面中显示。默认显示，如果只有一个账户时不显示
			'openEdit'			=> true,	//允许页面设置是否允许客员户端编辑
			'alias'				=>array(
					'displayname'=>array(
							'lang'=>'en',
							'name'=>'Display Name'
					),
					'展示名称'=>array(
							'lang'=>'cn',
							'name'=>'展示名称'
					),
			),
	),

	'loginname'=>array(
		//'pattern'			=>"/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/",//邮箱格式
		'pattern'			=>"/^\S{1,50}$/u",//匹配任意非空的字符串
		'js_pattern'		=>"/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/",//邮箱格式
		'tag_type'			=>0,	//0-必选标签1-可选标签 2-自定义标签
		'umsapifield' => 'loginName',//umsAPI对应字段名
		'enable'			=>true,	 //是否启用
		'show'				=>true,   //是否在标签管理页面中显示
		'alias'				=>array(
								'loginname'=>array(
												'lang'=>'en',
												'name'=>'loginname'
											),
								'帐号'=>array(
												'lang'=>'cn',
												'name'=>'帐号'
											),
		),
	),	

	'account'=>array(
		'pattern'			=>"/^.{0,50}$/u",
		'js_pattern'		=>"/^.{0,50}$/",
		'tag_type'			=>0,	   //0-必选标签1-可选标签 2-自定义标签
		'umsapifield' => 'accountId',//umsAPI对应字段名
		'enable'			=>false,	   //是否启用
		'show'				=>false,    //是否在标签管理页面中显示。默认显示，如果只有一个账户时不显示
		'alias'				=>array(
								'account'=>array(
												'lang'=>'en',
												'name'=>'account'
											),
								'账户'=>array(
												'lang'=>'cn',
												'name'=>'账户'
											),
		),
	),

	'position'=>array(
			'pattern'			=>"/^.{1,50}$/u",
			'js_pattern'		=>"/^\S{1,50}$/",
			'tag_type'			=>1,	   //0-必选标签1-可选标签 2-自定义标签
			'umsapifield' => 'position',//umsAPI对应字段名
			'enable'			=>true,	   //是否启用
			'show'				=>true,   //是否在标签管理页面中显示。
			'alias'				=>array(
					'position'=>array(
							'lang'=>'en',
							'name'=>'position'
					),
					'职位'=>array(
							'lang'=>'cn',
							'name'=>'职位',
					),
			),
	),

	'open'=>array(
		'pattern'			=>"/(^是$)|(^开$)|(^开启$)|(^否$)|(^关$)|(^关闭$)/u",
		'js_pattern'		=>"/^[01]$/",
		'tag_type'			=>0,	   //0-必选标签1-可选标签 2-自定义标签
		'umsapifield' => 'isopen',//umsAPI对应字段名
		'enable'			=>true,	   //是否启用
		'show'				=>false,   //是否在标签管理页面中显示。
		'alias'				=>array(
								'open'=>array(
											'lang'=>'en',
											'name'=>'open'
										),
								'开通帐号'=>array(
											'lang'=>'cn',
											'name'=>'开通帐号',
										),
		),
	),
	
	'department'=>array(
		'pattern'			=>"/^.{0,50}$/u",
		'js_pattern'		=>"/^\S{0,50}$/",
		'tag_type'			=>0,	   //0-必选标签1-可选标签 2-自定义标签
		'umsapifield' => 'organizationName',//umsAPI对应字段名
		'enable'			=>true,	   //是否启用
		'show'				=>true,   //是否在标签管理页面中显示。
		'alias'				=>array(
								'department'=>array(
											'lang'=>'en',
											'name'=>'department'
										),
								'部门'=>array(
											'lang'=>'cn',
											'name'=>'部门',
										),
		),
	),


	
	'mobile'=>array(
		'pattern'			=>"/^[\d]{5,20}$/",
		'js_pattern'		=>"/^[\d]{5,20}$/",
		'tag_type'			=>1,	   //0-必选标签1-可选标签 2-自定义标签
		'umsapifield' 		=> 'mobileNumber',//umsAPI对应字段名
		'enable'			=>true,	   //是否启用
		'show'				=>true,   //是否在标签管理页面中显示。
		'alias'				=>array(
								'mobile'=>array(
											'lang'=>'en',
											'name'=>'mobile'
										),
								'手机'=>array(
											'lang'=>'cn',
											'name'=>'手机',
										),
		),
	),
	
	//=================可选标签==================//
	
	'country'=>array(
		'pattern'			=>"/^.{1,50}$/u",
		'js_pattern'		=>"/^\S{1,50}$/",
		'tag_type'			=>1,	   //0-必选标签1-可选标签 2-自定义标签
		'umsapifield' 		=> 'countryCode',//umsAPI对应字段名
		'enable'			=>true,	   //是否启用
		'show'				=>true,   //是否在标签管理页面中显示。
		'alias'				=>array(
								'country'=>array(
											'lang'=>'en',
											'name'=>'country'
										),
								'国家'=>array(
											'lang'=>'cn',
											'name'=>'国家',
										),
		),
	),

	'officeaddress'=>array(
		'pattern'			=>"/^.{1,50}$/u",
		'js_pattern'		=>"/^\S{1,50}$/",
		'tag_type'			=>1,	   //0-必选标签1-可选标签 2-自定义标签
		'umsapifield' 		=> 'officeaddress',//umsAPI对应字段名
		'enable'			=>true,	   //是否启用
		'show'				=>true,   //是否在标签管理页面中显示。
		'alias'				=>array(
								'officeaddress'=>array(
											'lang'=>'en',
											'name'=>'officeaddress'
										),
								'办公室所在地'=>array(
											'lang'=>'cn',
											'name'=>'办公室所在地',
										),
		),
	),

	'email'=>array(
		'pattern'			=>"/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-_]+\.[a-zA-Z0-9-.]+$/",
		'js_pattern'		=>"/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/",
		'tag_type'			=>1,	   //0-必选标签1-可选标签 2-自定义标签
		'umsapifield' 		=> 'email',//umsAPI对应字段名
		'enable'			=>true,	   //是否启用
		'show'				=>true,   //是否在标签管理页面中显示。
		'alias'				=>array(
								'email'=>array(
											'lang'=>'en',
											'name'=>'email'
										),
								'邮箱'=>array(
											'lang'=>'cn',
											'name'=>'邮箱',
										),
		),
	),

	'officephone'=>array(
		'pattern'			=>"/^[\d]{5,20}$/",
		'js_pattern'		=>"/^[+]?[\d]{5,20}$/",
		'tag_type'			=>1,	   //0-必选标签1-可选标签 2-自定义标签
		'umsapifield' 		=> 'officePhone',//umsAPI对应字段名
		'enable'			=>true,	   //是否启用
		'show'				=>true,   //是否在标签管理页面中显示。
		'alias'				=>array(
								'officephone'=>array(
											'lang'=>'en',
											'name'=>'officephone'
										),
								'工作电话'=>array(
											'lang'=>'cn',
											'name'=>'工作电话',
										),
		),
	),

	'costcenter'=>array(
		'pattern'			=>"/^.{1,50}$/",
		'js_pattern'		=>"/^\S{1,50}$/",
		'tag_type'			=>1,	   //0-必选标签1-可选标签 2-自定义标签
		'enable'			=>false,	   //是否启用
		'show'				=>false,   //是否在标签管理页面中显示。
		'alias'				=>array(
								'costcenter'=>array(
											'lang'=>'en',
											'name'=>'costcenter'
										),
								'成本中心'=>array(
											'lang'=>'cn',
											'name'=>'成本中心',
										),
		),
	),

	'leader'=>array(
		'pattern'			=>"/^\S{1,50}$/",
		'js_pattern'		=>"/^\S{1,50}$/",
		'tag_type'			=>1,	   //0-必选标签1-可选标签 2-自定义标签
		'enable'			=>false,  //是否启用
		'show'				=>false,   //是否在标签管理页面中显示。
		'alias'				=>array(
								'leader'=>array(
											'lang'=>'en',
											'name'=>'leader'
										),
								'部门领导'=>array(
											'lang'=>'cn',
											'name'=>'部门领导',
										),
		),
	),

);

//=================自定义标签================//
$config['custom_tags'] = array(
								'pattern'=> "/^.{1,50}$/u",
								'js_pattern'=> "/^\S{1,50}$/",
							);


