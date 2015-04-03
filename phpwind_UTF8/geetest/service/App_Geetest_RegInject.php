<?php


defined('WEKIT_VERSION') || exit('Forbidden');

Wind::import('EXT:geetest.service.App_Geetest_Reg');

class App_Geetest_RegInject extends PwBaseHookInjector {

    public function run(){
        $App_Geetest_Reg = new App_Geetest_Reg($this->bp);
        return $App_Geetest_Reg;
    }
}