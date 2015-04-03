<?php
defined('WEKIT_VERSION') or exit(403);
/**
 * 后台菜单添加
 *
 * @author Break <877077145@qq.com>
 * @copyright www.geetest.com
 * @license www.geetest.com
 */
class App_Geetest_ConfigDo {
	
	/**
	 * 获取极验验证码后台菜单
	 *
	 * @param array $config
	 * @return array 
	 */
	public function getAdminMenu($config) {
		$config += array(
			'ext_geetest' => array('极验验证码', 'app/manage/*?app=geetest', '', '', 'appcenter'),
			);
		return $config;
	}
}

?>