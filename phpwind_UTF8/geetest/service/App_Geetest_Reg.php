<?php


defined('WEKIT_VERSION') || exit('Forbidden');

Wind::import('SRV:user.srv.register.do.PwRegisterDoBase');

class App_Geetest_Reg extends PwRegisterDoBase {
    
    public $config = array();
    
    public function __construct(PwRegisterService $pwpost) {
        $this->config = Wekit::load('config.PwConfig')->getValues('geetest');
        $this->publickey = $config['publickey'];
        $this->privatekey = $config['privatekey'];
        $this->config['model'] = json_decode($this->config['model'], true);
    }
    
    public function beforeRegister($userDm){
        if ($this->config['open'] == "1" && in_array("register", $this->config['model'])) {
            $challenge = Wind::getApp()->getRequest()->getRequest("geetest_challenge");
            $validate = Wind::getApp()->getRequest()->getRequest("geetest_validate");
            $seccode = Wind::getApp()->getRequest()->getRequest("geetest_seccode");
            
            $response = $this->_getTools()->geetest_validate($challenge, $validate, $seccode);
            if($response != 1){
                if($response == -1){
                    return new PwError('错误：滑动验证不通过！请再次滑动，通过验证！');
                }else if($response == 0){
                    return new PwError('错误：请不要再次提交！滑动验证已使用过，请刷新之后在使用！');
                }
            }
        }
    }
    
    public function _getTools(){
        return Wekit::load('EXT:geetest.service.App_Geetest_Service');
    }
}