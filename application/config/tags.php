<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 标签(系统预定义标签（必选、可选）、自定义标签)
 * @file tag.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */

//0-必选标签1-可选标签 2-部门标签 3-自定义标签

$config['system_tags'] = array(
	'1b992ad7549a44bae725f4a29577a0cf'=>array(
		'name'=>'姓',
		'en_name'=>'lastname',
		//'pattern'=>"/^[\x{4e00}-\x{9fa5}a-zA-Z0-9]{1,50}$/u";//1到50个中英文数字
		'pattern'=>"/^\S{1,50}$/u",//1到50个中英文字符或者数字。注意此处实际填写的是姓名
		'tag_type'=>0,//0-必选标签1-可选标签 2-部门标签 3-自定义标签
	),
	'4f8ca95e7b8ed340400a6e2be1265287'=>array(
		'name'=>'名',
		'en_name'=>'firstname',
		'pattern'=>"/^.{0,50}$/u",//1到50个中英文字符或者数字。,可以为空
		'tag_type'=>0,
	),
	'2c591eb467dca0927d97b98ce6004f19'=>array(
		'name'=>'帐号',
		'en_name'=>'loginname',
		'pattern'=>"/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/u",//邮箱格式
		'tag_type'=>0,
	),
	'7116e7ec169a0780408f1ccea427b9ac'=>array(
		'name'=>'账户',
		'en_name'=>'account',
		'pattern'=>"/^.{0,50}$/u",
		'tag_type'=>0,
	),
	'04912174a63a152296eafea32c6b76f3'=>array(
		'name'=>'开启帐号',
		'en_name'=>'open',
		'pattern'=>"/(^是$)|(^开$)|(^开启$)|(^否$)|(^关$)|(^关闭$)/u",
		'tag_type'=>0,
	),
	'787b5677e325beec788727cd1a327f1f'=>array(
		'name'=>'性别',
		'en_name'=>'sex',
		'pattern'=>"/(^男$)|(^女$)/u",
		'tag_type'=>0,
	),
	
	'b652de80d6a54f83ce3cb04aaddc0c7e'=>array(
			'name'=>'职位',
			'en_name'=>'position',
			'pattern'=>"/^\S{1,50}$/u",
			'tag_type'=>0,
	),
	'9f9d36327d96efaefe09f6bda9796531'=>array(
			'name'=>'手机',
			'en_name'=>'mobile',
			'pattern'=>"/^[+]?[\d]{5,20}$/u",
			'tag_type'=>0,
	),
	'ee3f5585b166e4e8fe9513fade51cd15'=>array(
			'name'=>'国家',
			'en_name'=>'country',
			'pattern'=>"/^\S{1,50}$/u",
			'tag_type'=>0,
	),
	'f0aca4d3549b65a6ae1c832a8b3fbe34'=>array(
			'name'=>'办公室所在地区',
			'en_name'=>'officeaddress',
			'pattern'=> "/^\S{1,50}$/u",
			'tag_type'=>0,
	),
	
	'f1b69adc8ee1a39c5cb5ac327b6e8950'=>array(
		'name'=>'部门一级',
		'en_name'=>'department1',
		'pattern'=>"/^\S{1,50}$/u",
		'tag_type'=>2,
	),
	'30a16dd9de6499be1ef9bd09ae67cde2'=>array(
		'name'=>'部门二级',
		'en_name'=>'department2',
		'pattern'=>"/^.{0,50}$/u",
		'tag_type'=>2,
	),
	'83c2729f0bc10b488a0586e78fad2c56'=>array(
		'name'=>'部门三级',
		'en_name'=>'department3',
		'pattern'=>"/^.{0,50}$/u",
		'tag_type'=>2,
	),
	'a8d1bd3beaa0a8e7b5e2f97bccc99440'=>array(
		'name'=>'部门四级',
		'en_name'=>'department4',
		'pattern'=>"/^.{0,50}$/u",
		'tag_type'=>2,
	),
	'0789b7cfe7968bd91e04803afcb24c24'=>array(
		'name'=>'部门五级',
		'en_name'=>'department5',
		'pattern'=>"/^.{0,50}$/u",
		'tag_type'=>2,
	),
	'edd3bed37594697102fbf21e94dcffa3'=>array(
		'name'=>'部门六级',
		'en_name'=>'department6',
		'pattern'=>"/^.{0,50}$/u",
		'tag_type'=>2,
	),
	'd0616f7676d07b418c6c892c7a38cd93'=>array(
		'name'=>'部门七级',
		'en_name'=>'department7',
		'pattern'=>"/^.{0,50}$/u",
		'tag_type'=>2,
	),
	'5178a4cc3d1907d26696636d4f91bc87'=>array(
		'name'=>'部门八级',
		'en_name'=>'department8',
		'pattern'=>"/^.{0,50}$/u",
		'tag_type'=>2,
	),
	'c050793bd13249fd53e8415ea1bf2f2f'=>array(
		'name'=>'部门九级',
		'en_name'=>'department9',
		'pattern'=>"/^.{0,50}$/u",
		'tag_type'=>2,
	),
	'ae66c83fd501c2292f6b272dc1d95793'=>array(
		'name'=>'部门十级',
		'en_name'=>'department10',
		'pattern'=>"/^.{0,50}$/u",
		'tag_type'=>2,
	),
	
	'3bc5e602b2d4c7fffe79258e2ac6952e'=>array(
		'name'=>'邮箱',
		'en_name'=>'email',
		'pattern'=>"/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/u",
		'tag_type'=>1,
	),
	'c7db259ed63afaba9629dd62fe6046e5'=>array(
		'name'=>'工作电话',
		'en_name'=>'officephone',
		'pattern'=>"/^[+]?[\d]{5,20}$/",
		'tag_type'=>1,
	),
	'0903eff894f1d8bbf83b11e70dd74d82'=>array(
		'name'=>'成本中心',
		'en_name'=>'costcenter',
		'pattern'=>"/^\S{1,50}$/",
		'tag_type'=>1,
	),
	'efa9311af1b734f10f8b3eb0de5bc1a9'=>array(
		'name'=>'员工ID',
		'en_name'=>'staffid',
		'pattern'=> "/^\d+$/",
		'tag_type'=>1,
	),
);

$config['custom_tags'] = array(
	array(
		'name'=>'自定义标签',
		'en_name'=>'custom_tag',
		'pattern'=> "/^.{0,50}$/",
		'tag_type'=>3,
	),
);

