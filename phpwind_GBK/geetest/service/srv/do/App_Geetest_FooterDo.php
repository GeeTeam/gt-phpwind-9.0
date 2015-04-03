<?php 
class App_Geetest_FooterDo{

	public function run(){
		$config = Wekit::load('config.PwConfig')->getValues('geetest');
		$publickey = $config['publickey'];
		$config['model'] = json_decode($config['model'],true);
		
		$m=Wind::getApp()->getResponse()->getData('_aCloud_');
		$mc=$m['m'].'/'.$m['c'];
		$post_run = Wind::getApp()->getResponse()->getData('post_run');
		$uid = @Wekit::app()->getLoginUser()->uid;
        		$action = $post_run['action'];
        		if ($this->_getTools()->register($publickey)) {
        			
			switch ($mc) {
				case 'u/register':
					if ($config['open'] == "1" && in_array("register", $config['model'])) {
						$btn_id = "register_id";
						$codejs = $this->fix_other_login($btn_id,$publickey).$this->fix_main_register();
					}
					break;
				case 'u/login':
					if ($config['open'] == "1" && in_array("login", $config['model'])) {
						$btn_id = "login_id";
						$codejs = $this->fix_other_login($btn_id,$publickey).$this->fix_main_login();	
					}
					break;
				case 'bbs/post':
					if (in_array($action, $config['model']) && $config['open'] == "1") {
						$btn_id = "J_post_sub";
						$codejs = $this->fix_other_login($btn_id,$publickey).$this->fix_main_post();
					}
					break;
				case 'bbs/read':
					if (!$uid && in_array("login", $config['model']) && $config['open'] == "1"){
							$btn_id = 'J_qlogin_login';
							$codejs = $this->fix_other_login($btn_id,$publickey);
					}
					break;
				case in_array($mc,array('bbs/index','appcenter/index','bbs/thread','tag/index')):
					if ($config['open'] == "1" && in_array("login", $config['model'])) {
						$btn_id = 'J_sidebar_login';
						$codejs = $this->fix_other_login($btn_id,$publickey);
					}
					break;
				default:
					if ($config['open'] == "1" &&　in_array("login", $config['model'])) {
						$btn_id = 'J_qlogin_login';
						$codejs = $this->fix_other_login($btn_id,$publickey);
					}
					break;
			}
			echo $codejs;
        		}
		
	}
	
	
//--------------------------------------------------------------------------------------------

	
	public function fix_main_post(){
		$echo_js = <<<JS
		<script type="text/javascript">
			Wind.ready(document,function(){			
        		var post = document.getElementById('J_post_sub');
        		var geetest = document.getElementById('gt_popup_id');
            	post.parentNode.insertBefore(geetest, post);
			});
		</script>
JS;
	return $echo_js;
	}

//--------------------------------------------------------------------------------------------
	public function fix_main_register(){
		$echo_js = <<<JS
		<script type="text/javascript">
		window.onload = function(){
	        		var register = document.getElementsByClassName('btn_submit');
	        		register[0].setAttribute('id', 'register_id');
	        		var geetest = document.getElementById('gt_popup_id');
	        		var form = document.getElementById('J_register_form');
	        		form.appendChild(geetest);
	            		// register[0].parentNode.insertBefore(geetest, register[0]);
		}		        
		</script>
JS;
	return $echo_js;
	}

//--------------------------------------------------------------------------------------------

//修复主登陆界面验证码位置
	public function fix_main_login(){
		$echo_js = <<<JS
		<script type="text/javascript">
			Wind.ready(document,function(){			
        		var login = document.getElementsByClassName('btn_submit');
        		login[0].setAttribute('id', 'login_id');
        		var geetest = document.getElementById('gt_popup_id');
            	login[0].parentNode.insertBefore(geetest, login);
			});
		</script>
JS;
	return $echo_js; 
	}
//--------------------------------------------------------------------------------------------
//验证条
	private function geetest_js($publickey){
		
		return '<script type="text/javascript" src="http://api.geetest.com/get.php?gt='.$publickey.'"></script>';
		
	}
	public function fix_other_login($btn_id,$publickey,$product="popup"){
		
		return $this->_getTools()->get_widget($btn_id,$publickey,$product="popup");
	}

    public function _getTools(){
        return Wekit::load('EXT:geetest.service.App_Geetest_Service');
    }
}

 ?>