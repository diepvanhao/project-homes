<?php

require(HOME_ROOT.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'smarty'.DIRECTORY_SEPARATOR.'Smarty.class.php');

class HOMESmarty extends Smarty
{
  var $_tpl_hooks;
  
  var $_tpl_hooks_no_multi = TRUE;
  
  
  
  function HOMESmarty()
  {
      
//    $this->template_dir = HOME_ROOT.DIRECTORY_SEPARATOR.'templates/';
//    $this->compile_dir = HOME_ROOT.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'smarty'.DIRECTORY_SEPARATOR.'templates_c/';
//    $this->cache_dir = HOME_ROOT.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'smarty'.DIRECTORY_SEPARATOR.'cache/';
//    $this->config_dir = HOME_ROOT.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'smarty'.DIRECTORY_SEPARATOR.'configs/';
//     $this->caching=  Smarty::CACHING_LIFETIME_CURRENT;
      parent::__construct();      

        $this->setTemplateDir(HOME_ROOT.DIRECTORY_SEPARATOR.'templates/');
        
        $this->setCompileDir(HOME_ROOT.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'smarty'.DIRECTORY_SEPARATOR . 'templates_c/');
        $this->setConfigDir(HOME_ROOT.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'smarty'.DIRECTORY_SEPARATOR . 'configs/');
        $this->setCacheDir(HOME_ROOT.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'smarty'.DIRECTORY_SEPARATOR. 'cache/');
        //$this->caching=  Smarty::CACHING_LIFETIME_CURRENT;
    
  }
  
  
  
  static function &getInstance()
  {
    static $instance;
    
    if( is_null($instance) )
    {
      $instance = new HOMESmarty();
    }
    
    return $instance;
  }
  
  
  
  function assign_hook($hook, $include)
  {
    if( !isset($this->_tpl_hooks[$hook]) )
      $this->_tpl_hooks[$hook] = array();
    
    if( $this->_tpl_hooks_no_multi && in_array($include, $this->_tpl_hooks[$hook]) )
      return;
    
    $this->_tpl_hooks[$hook][] = $include;
  }
}

?>