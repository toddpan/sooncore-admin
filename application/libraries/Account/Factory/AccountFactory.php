<?php
/**
 * 账户处理工厂类
 * @file AccountFactory.php
 * @author caohongliang <hongliang.cao@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */

abstract class AccountFactory{
	
	/**
	 * 分发处理实例
	 * @param int $type 操作类型
	 */
	abstract public function get_instance($type);
}