<?php


defined('WEKIT_VERSION') || exit('Forbidden');

Wind::import('EXT:geetest.service.App_geetest_Other');

class App_geetest_Other extends PwBaseHookInjector {

    public function run(){
        $App_geetest_Other = new App_geetest_Other($this->bp);
        return $App_geetest_Other;
    }
}