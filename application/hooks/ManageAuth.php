<?php
/**
 * 后台权限拦截钩子
 */
class ManageAuth {

	private $CI;

	public function __construct() {
		$this -> CI = &get_instance();
	}

	/**
	 * 验证用户是否登录等
	 */
	public function auth() {
		$this -> CI -> load -> helper('url');
		if (preg_match("/welcome.*/i", uri_string())) {//TODO 不需要进行权限检查的URL
			return;
		}
                //登陆
		if (preg_match("/Login.*/i", uri_string())) {//TODO 不需要进行权限检查的URL
			return;
		}
                //忘记密码
		if (preg_match("/ResetPassword.*/i", uri_string())) {//TODO 不需要进行权限检查的URL
			return;
		}
		$this -> CI -> load -> library('session');
		if (!$this -> CI -> session -> userdata('username')) {//TODO 验证用户是否登录
			redirect('/welcome');
			return;
		}

	}

}
