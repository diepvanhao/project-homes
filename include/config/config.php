<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
define('SMARTY_DIR', str_replace("\\", "/", getcwd()) . '/include/smarty/libs/');
define('CURRENT_DIR', str_replace("\\", "/", getcwd()) . '/');
//echo CURRENT_DIR;die();
require (SMARTY_DIR.'Smarty.class.php');

class smarty_class extends Smarty {

    public function __construct() {
        parent::__construct();      

        $this->setTemplateDir(CURRENT_DIR.'templates/');
        
        $this->setCompileDir(SMARTY_DIR . 'template_c/');
        $this->setConfigDir(SMARTY_DIR . 'configs/');
        $this->setCacheDir(SMARTY_DIR . 'cache/');
        
        $this->caching=  Smarty::CACHING_LIFETIME_CURRENT;
        
    }

}
